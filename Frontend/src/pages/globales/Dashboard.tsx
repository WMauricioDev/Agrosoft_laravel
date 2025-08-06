import { useState } from "react";
import DefaultLayout from "@/layouts/default";
import { motion } from "framer-motion";
import { BarChart, Bar, XAxis, YAxis, Tooltip, PieChart, Pie, Cell, ResponsiveContainer } from "recharts";
import CustomSpinner from "@/components/globales/Spinner";
import { useVenta } from "@/hooks/finanzas/useVenta";
import { useActividades } from "@/hooks/cultivo/useActividad";
import { useInsumos } from "@/hooks/inventario/useInsumo";
import { FaDollarSign, FaBox } from "react-icons/fa";
import { useNavigate } from "react-router-dom";

// Definimos una interfaz para los datos mensuales (simulados)
interface MonthlyData {
  mes: string;
  ingresos: number;
  costos: number;
}

const Dashboard = () => {
  const currentDate = new Date();
  const navigate = useNavigate();

  // Hooks para obtener datos
  const { ventas, isLoading: loadingVentas, isError: errorVentas, error: errorVentasError } = useVenta();
const { data: actividadesData, isLoading: loadingActividades, error: errorActividades } = useActividades();
  const { data: insumos, isLoading: loadingInsumos, error: errorInsumos } = useInsumos();

  // Procesar datos de actividades
  console.log("🔴actividades:", actividadesData, typeof actividadesData);
const activities = (actividadesData?.data ?? []).map((actividad) => ({
  id: actividad.id || `${actividad.descripcion}-${actividad.fecha_inicio}`,
  title: actividad.descripcion || "Actividad sin descripción",
  date: actividad.fecha_inicio?.split("T")[0] || currentDate.toISOString().split("T")[0],
  time: actividad.fecha_inicio?.split("T")[1]?.slice(0, 5) || "00:00",
  estado: actividad.estado || "PENDIENTE",
}));


  const pastActivities = activities.filter((activity) => new Date(activity.date) < currentDate);
  const futureActivities = activities.filter((activity) => new Date(activity.date) >= currentDate);

  // Datos para las tarjetas superiores
  const ganancias = ventas.reduce((acc, venta) => {
    const ingreso = (venta.monto_entregado || 0) - (venta.cambio || 0);
    return acc + ingreso;
  }, 0) || 0;
  const totalInsumos = insumos?.length || 0;

  // Datos para el gráfico de barras (ingresos y costos por mes)
  const barChartData: MonthlyData[] = ventas.length > 0
    ? [
        {
          mes: currentDate.toLocaleDateString("es-ES", { month: "short", year: "numeric" }),
          ingresos: ganancias,
          costos: 0, // No tenemos datos de costos, se puede ajustar si se agrega un endpoint para costos
        },
      ]
    : [];

  // Datos para el gráfico circular (progreso de actividades)
  const completedActivities = activities.filter((a) => a.estado === "COMPLETADA").length;
  const pendingActivities = activities.filter((a) => a.estado === "PENDIENTE").length;
  const pieChartData = [
    { name: "Completado", value: completedActivities },
    { name: "Pendiente", value: pendingActivities },
  ];

  const COLORS = ["#10b981", "#1e3a8a"];

  // Mostrar un spinner mientras se cargan los datos
  if (loadingVentas || loadingActividades || loadingInsumos) {
    return (
      <DefaultLayout>
        <div className="flex justify-center items-center h-screen">
          <CustomSpinner label="Cargando datos..." color="primary" variant="wave" className="text-primary" />
        </div>
      </DefaultLayout>
    );
  }

  // Mostrar errores si los hay
  if (errorVentas || errorActividades || errorInsumos) {
    return (
      <DefaultLayout>
        <div className="text-center py-12 text-red-500">
          <p className="text-xl">Error al cargar los datos</p>
          <p>{errorVentasError?.message || errorActividades?.message || errorInsumos?.message}</p>
          <motion.button
            className="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:bg-blue-700"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            onClick={() => navigate('/login')}
          >
            Iniciar Sesión
          </motion.button>
        </div>
      </DefaultLayout>
    );
  }

  return (
    <DefaultLayout>
      <h1 className="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-gray-800">Dashboard</h1>

      {/* Tarjetas superiores */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <motion.div
          className="bg-white rounded-xl shadow-md p-6 text-center border border-green-200"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <p className="text-lg font-semibold text-gray-700 flex items-center justify-center gap-2">
            <FaDollarSign className="text-green-500" /> Ganancias
          </p>
          <p className="text-2xl sm:text-3xl font-bold mt-2 text-green-600">${ganancias.toLocaleString()}</p>
        </motion.div>
        <motion.div
          className="bg-white rounded-xl shadow-md p-6 text-center border border-yellow-200"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.1 }}
        >
          <p className="text-lg font-semibold text-gray-700 flex items-center justify-center gap-2">
            <FaBox className="text-yellow-500" /> Insumos
          </p>
          <p className="text-2xl sm:text-3xl font-bold mt-2 text-yellow-600">{totalInsumos}</p>
        </motion.div>
      </div>

      {/* Gráficos */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <motion.div
          className="bg-white p-6 rounded-xl shadow-md"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <h2 className="text-lg font-semibold text-gray-700 mb-4">Ganancias y Costos</h2>
          <ResponsiveContainer width="100%" height={250}>
            <BarChart data={barChartData}>
              <XAxis dataKey="mes" />
              <YAxis />
              <Tooltip />
              <Bar dataKey="ingresos" fill="#10b981" />
              <Bar dataKey="costos" fill="#1e3a8a" />
            </BarChart>
          </ResponsiveContainer>
        </motion.div>
        <motion.div
          className="bg-white p-6 rounded-xl shadow-md"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.2 }}
        >
          <h2 className="text-lg font-semibold text-gray-700 mb-4">Progreso de Actividades</h2>
          <ResponsiveContainer width="100%" height={200}>
            <PieChart>
              <Pie
                data={pieChartData}
                cx="50%"
                cy="50%"
                innerRadius={60}
                outerRadius={80}
                fill="#8884d8"
                dataKey="value"
              >
                {pieChartData.map((_, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip />
            </PieChart>
          </ResponsiveContainer>
          <p className="text-center text-sm mt-2 text-gray-700">
            {completedActivities + pendingActivities > 0
              ? `${((completedActivities / (completedActivities + pendingActivities)) * 100).toFixed(1)}% Completado`
              : "0% Completado"}
          </p>
        </motion.div>
      </div>

      {/* Actividades */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <motion.div
          className="bg-white p-6 rounded-xl shadow-md border border-purple-200"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5 }}
        >
          <h2 className="text-lg font-semibold text-gray-700 mb-4">Actividades Futuras</h2>
          <ul className="list-disc list-inside text-gray-700 space-y-2 text-sm">
            {futureActivities.slice(0, 3).map((activity) => (
              <li key={activity.id}>
                {activity.title} - {activity.date}
              </li>
            ))}
            {futureActivities.length === 0 && (
              <li className="text-gray-500">No hay actividades futuras</li>
            )}
          </ul>
        </motion.div>
        <motion.div
          className="bg-white p-6 rounded-xl shadow-md border border-purple-200"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: 0.1 }}
        >
          <h2 className="text-lg font-semibold text-gray-700 mb-4">Actividades Vencidas</h2>
          <ul className="list-disc list-inside text-gray-700 space-y-2 text-sm">
            {pastActivities.slice(0, 3).map((activity) => (
              <li key={activity.id}>
                {activity.title} - {activity.date}
              </li>
            ))}
            {pastActivities.length === 0 && (
              <li className="text-gray-500">No hay actividades vencidas</li>
            )}
          </ul>
          <motion.button
            className="mt-4 bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-md hover:bg-purple-700"
            whileHover={{ scale: 1.05 }}
            whileTap={{ scale: 0.95 }}
            onClick={() => navigate('/cultivo/listaractividad')}
          >
            Revisar Ahora
          </motion.button>
        </motion.div>
      </div>
    </DefaultLayout>
  );
};

export default Dashboard;