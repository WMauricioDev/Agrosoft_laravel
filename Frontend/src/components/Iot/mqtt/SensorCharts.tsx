import React, { useMemo } from "react";
import { motion } from "framer-motion";
import { Bar, Line } from "react-chartjs-2";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  LineElement,
  PointElement,
  Title,
  Tooltip,
  Legend,
  ChartOptions,
  ChartData,
  ChartDataset,
} from "chart.js";
import { SensorChartsProps } from "@/types/iot/iotmqtt";

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Title, Tooltip, Legend);

export const SensorCharts: React.FC<SensorChartsProps> = ({ realTimeData, selectedDataType, selectedSensor = "todos" }) => {
  // Datos para el gráfico de barras
  const barChartData: ChartData<"bar", number[], string> = useMemo(() => {
    const filteredData = realTimeData
      .filter(
        (dato) =>
          dato[selectedDataType.key] !== null &&
          dato[selectedDataType.key] !== undefined &&
          (selectedSensor === "todos" || dato.device_code === selectedSensor.toString())
      )
      .sort((a, b) => (new Date(b.fecha_medicion || "").getTime() - new Date(a.fecha_medicion || "").getTime()))
      .slice(0, 20);

    const labels = filteredData.map((dato) =>
      dato.fecha_medicion
        ? new Date(dato.fecha_medicion).toLocaleString("es-ES", {
            hour: "2-digit",
            minute: "2-digit",
          })
        : "N/A"
    );

    const datasets: ChartDataset<"bar", number[]>[] = [
      {
        label: selectedDataType.nombre,
        data: filteredData.map((dato) => dato[selectedDataType.key] as number),
        backgroundColor:
          selectedDataType.key === "temperatura"
            ? "rgba(220, 38, 38, 0.2)"
            : selectedDataType.key === "humedad_ambiente"
            ? "rgba(37, 99, 235, 0.2)"
            : selectedDataType.key === "humedad_suelo"
            ? "rgba(16, 185, 129, 0.2)"
            : selectedDataType.key === "calidad_aire"
            ? "rgba(245, 158, 11, 0.2)"
            : "rgba(245, 158, 11, 0.2)",
      },
    ];

    return { labels, datasets };
  }, [realTimeData, selectedDataType, selectedSensor]);

  // Datos para el gráfico de líneas
  const lineChartData: ChartData<"line", number[], string> = useMemo(() => {
    const filteredData = realTimeData
      .filter(
        (dato) =>
          dato[selectedDataType.key] !== null &&
          dato[selectedDataType.key] !== undefined &&
          (selectedSensor === "todos" || dato.device_code === selectedSensor.toString())
      )
      .sort((a, b) => (new Date(b.fecha_medicion || "").getTime() - new Date(a.fecha_medicion || "").getTime()))
      .slice(0, 20);

    const labels = filteredData.map((dato) =>
      dato.fecha_medicion
        ? new Date(dato.fecha_medicion).toLocaleString("es-ES", {
            hour: "2-digit",
            minute: "2-digit",
          })
        : "N/A"
    );

    const datasets: ChartDataset<"line", number[]>[] = [
      {
        label: selectedDataType.nombre,
        data: filteredData.map((dato) => dato[selectedDataType.key] as number),
        borderColor:
          selectedDataType.key === "temperatura"
            ? "#dc2626"
            : selectedDataType.key === "humedad_ambiente"
            ? "#2563eb"
            : selectedDataType.key === "humedad_suelo"
            ? "#10b981"
            : selectedDataType.key === "calidad_aire"
            ? "#f59e0b"
            : "#f59e0b",
        backgroundColor:
          selectedDataType.key === "temperatura"
            ? "rgba(220, 38, 38, 0.2)"
            : selectedDataType.key === "humedad_ambiente"
            ? "rgba(37, 99, 235, 0.2)"
            : selectedDataType.key === "humedad_suelo"
            ? "rgba(16, 185, 129, 0.2)"
            : selectedDataType.key === "calidad_aire"
            ? "rgba(245, 158, 11, 0.2)"
            : "rgba(245, 158, 11, 0.2)",
        fill: true,
        tension: 0.4,
      },
    ];

    if (selectedDataType.medida_minima !== undefined) {
      datasets.push({
        label: `Mínimo (${selectedDataType.medida_minima})`,
        data: Array(labels.length).fill(selectedDataType.medida_minima),
        borderColor: "#ef4444",
        fill: false,
        borderDash: [5, 5],
      });
    }

    if (selectedDataType.medida_maxima !== undefined) {
      datasets.push({
        label: `Máximo (${selectedDataType.medida_maxima})`,
        data: Array(labels.length).fill(selectedDataType.medida_maxima),
        borderColor: "#f59e0b",
        fill: false,
        borderDash: [5, 5],
      });
    }

    return { labels, datasets };
  }, [realTimeData, selectedDataType, selectedSensor]);

  const options: ChartOptions<"line" | "bar"> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: { position: "top" as const },
      title: { display: true, font: { size: 16 } },
      tooltip: {
        enabled: true,
        callbacks: {
          label: (context) =>
            `${context.dataset.label}: ${context.parsed.y.toFixed(context.datasetIndex === 0 ? selectedDataType.decimals : 0)}`,
        },
      },
    },
    scales: {
      y: {
        title: { display: true, text: selectedDataType.nombre },
        grid: { color: "rgba(0, 0, 0, 0.1)" },
      },
      x: {
        title: { display: true, text: "Hora" },
        grid: { display: false },
      },
    },
    animation: {
      duration: 1000,
    },
  };

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 gap-6 w-full max-w-6xl mx-auto">
      <motion.div
        className="bg-white p-6 rounded-lg shadow-md h-96"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        <h2 className="text-lg font-semibold mb-4 text-gray-800">{selectedDataType.nombre} por Hora</h2>
        <Bar
          data={barChartData}
          options={{
            ...options,
            plugins: { ...options.plugins, title: { ...options.plugins?.title, text: `${selectedDataType.nombre} por Hora` } },
          }}
        />
        {barChartData.labels?.length === 0 && <p className="text-gray-600 text-center mt-4">No hay datos disponibles.</p>}
      </motion.div>
      <motion.div
        className="bg-white p-6 rounded-lg shadow-md h-96"
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
      >
        <h2 className="text-lg font-semibold mb-4 text-gray-800">{selectedDataType.nombre} en el Tiempo</h2>
        <Line
          data={lineChartData}
          options={{
            ...options,
            plugins: { ...options.plugins, title: { ...options.plugins?.title, text: `${selectedDataType.nombre} en el Tiempo` } },
          }}
        />
        {lineChartData.labels?.length === 0 && <p className="text-gray-600 text-center mt-4">No hay datos disponibles.</p>}
      </motion.div>
    </div>
  );
};

export default SensorCharts;