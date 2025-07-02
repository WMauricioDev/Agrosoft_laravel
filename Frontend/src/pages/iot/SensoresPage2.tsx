import React, { useState, useRef, useMemo, useCallback, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import { FaTemperatureHigh, FaTint, FaLeaf, FaWind, FaLightbulb } from "react-icons/fa";
import DefaultLayout from "@/layouts/default";
import { useSensores } from "@/hooks/iot/mqtt/useSensores";
import { usePublishCommand } from "@/hooks/iot/mqtt/usePublishCommand";
import { useRegistrarDatosMeteorologicos } from "@/hooks/iot/datos_sensores/useRegistrarDatosMeteorologicos"; // Importar el hook
import { DataTypeSelector } from "@/components/Iot/mqtt/DataTypeSelector";
import { ViewModeSelector } from "@/components/Iot/mqtt/ViewModeSelector";
import { SensorCharts } from "@/components/Iot/mqtt/SensorCharts";
import { SensorStats } from "@/components/Iot/mqtt/SensorStats";
import { SensorTable } from "@/components/Iot/mqtt/SensorTable";
import { GenerateReport } from "@/components/Iot/mqtt/GenerateReport";
import { DataType, ViewMode } from "@/types/iot/iotmqtt";
import { Chart as ChartJS, CategoryScale, LinearScale, BarElement, LineElement, PointElement, Title, Tooltip, Legend } from "chart.js";
import { debounce } from "lodash";

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Title, Tooltip, Legend);

const dataTypes: DataType[] = [
  {
    nombre: "Temperatura (°C)",
    key: "temperatura",
    icon: <FaTemperatureHigh className="text-red-500" />,
    tipo_sensor: "temperatura",
    decimals: 2,
    medida_minima: 10,
    medida_maxima: 40,
  },
  {
    nombre: "Humedad Ambiente (%)",
    key: "humedad_ambiente",
    icon: <FaTint className="text-blue-500" />,
    tipo_sensor: "humedad_ambiente",
    decimals: 1,
    medida_minima: 20,
    medida_maxima: 90,
  },
  {
    nombre: "Humedad Suelo (%)",
    key: "humedad_suelo",
    icon: <FaLeaf className="text-green-500" />,
    tipo_sensor: "humedad_suelo",
    decimals: 1,
    medida_minima: 10,
    medida_maxima: 80,
  },
  {
    nombre: "Calidad Aire (PPM)",
    key: "calidad_aire",
    icon: <FaWind className="text-yellow-500" />,
    tipo_sensor: "calidad_aire",
    decimals: 0,
    medida_minima: 0,
    medida_maxima: 1000,
  },
  {
    nombre: "Luminosidad (lux)",
    key: "luminosidad",
    icon: <FaLightbulb className="text-amber-500" />,
    tipo_sensor: "luminosidad",
    decimals: 0,
    medida_minima: 0,
    medida_maxima: 10000,
  },
];

const viewModes: ViewMode[] = [
  { id: "realtime", label: "Tiempo Real" },
  { id: "allData", label: "Todos los Datos" },
];

const SensoresPage: React.FC = () => {
  const { realTimeData, isLoading, error, mqttClient } = useSensores();
  const [sensorActive, setSensorActive] = useState(true);
  const [selectedDataType, setSelectedDataType] = useState<DataType>(dataTypes[0]);
  const [selectedViewMode, setSelectedViewMode] = useState<ViewMode>(viewModes[0]);
  const [bufferedData, setBufferedData] = useState<any[]>([]); // Buffer para acumular datos
  const publishCommand = usePublishCommand(mqttClient, sensorActive, setSensorActive);
  const { mutate: registrarDatos } = useRegistrarDatosMeteorologicos(); // Hook para registrar datos
  const navigate = useNavigate();
  const chartRef = useRef<HTMLDivElement>(null);

  const debouncedSetSelectedDataType = useCallback(debounce(setSelectedDataType, 300), []);

  // Filtrar datos por ESP32_001
  const filteredData = useMemo(() => realTimeData.filter((d) => d.device_code === "ESP32_001"), [realTimeData]);

  // Acumular datos en el buffer cada vez que se reciben nuevos datos
  useEffect(() => {
    if (filteredData.length > 0) {
      setBufferedData((prev) => [...prev, ...filteredData]);
    }
  }, [filteredData]);

  // Calcular promedio y registrar cada 2 minutos
  useEffect(() => {
    const interval = setInterval(() => {
      if (bufferedData.length === 0) return;

      // Calcular promedios
      const averages = bufferedData.reduce(
        (acc, curr) => {
          if (curr.temperatura != null) {
            acc.temperatura.sum += curr.temperatura;
            acc.temperatura.count += 1;
          }
          if (curr.humedad_ambiente != null) {
            acc.humedad_ambiente.sum += curr.humedad_ambiente;
            acc.humedad_ambiente.count += 1;
          }
          if (curr.humedad_suelo != null) {
            acc.humedad_suelo.sum += curr.humedad_suelo;
            acc.humedad_suelo.count += 1;
          }
          if (curr.calidad_aire != null) {
            acc.calidad_aire.sum += curr.calidad_aire;
            acc.calidad_aire.count += 1;
          }
          if (curr.luminosidad != null) {
            acc.luminosidad.sum += curr.luminosidad;
            acc.luminosidad.count += 1;
          }
          return acc;
        },
        {
          temperatura: { sum: 0, count: 0 },
          humedad_ambiente: { sum: 0, count: 0 },
          humedad_suelo: { sum: 0, count: 0 },
          calidad_aire: { sum: 0, count: 0 },
          luminosidad: { sum: 0, count: 0 },
        }
      );

      // Preparar payload para el registro
      const payload = {
        device_code: "ESP32_001",
        temperatura: averages.temperatura.count > 0 ? averages.temperatura.sum / averages.temperatura.count : null,
        humedad_ambiente: averages.humedad_ambiente.count > 0 ? averages.humedad_ambiente.sum / averages.humedad_ambiente.count : null,
        humedad_suelo: averages.humedad_ambiente.count > 0 ? averages.humedad_suelo.sum / averages.humedad_suelo.count : null,
        calidad_aire: averages.calidad_aire.count > 0 ? averages.calidad_aire.sum / averages.calidad_aire.count : null,
        luminosidad: averages.luminosidad.count > 0 ? averages.luminosidad.sum / averages.luminosidad.count : null,
        fecha_medicion: new Date().toISOString(),
      };

      // Registrar los datos promedio
      if (Object.values(payload).some((value) => value !== null && value !== "ESP32_001")) {
        registrarDatos(payload);
      }

      // Limpiar el buffer
      setBufferedData([]);
    }, 120000); // 2 minutos en milisegundos

    return () => clearInterval(interval);
  }, [bufferedData, registrarDatos]);

  if (isLoading) {
    return (
      <DefaultLayout>
        <div className="w-full flex flex-col items-center justify-center min-h-screen bg-gray-50">
          <p className="text-gray-700">Cargando datos...</p>
        </div>
      </DefaultLayout>
    );
  }

  if (error) {
    return (
      <DefaultLayout>
        <div className="w-full flex flex-col items-center justify-center min-h-screen bg-gray-50">
          <p className="text-red-500 text-center">{error}</p>
          <button
            className="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
            onClick={() => window.location.reload()}
          >
            Reintentar
          </button>
        </div>
      </DefaultLayout>
    );
  }

  return (
    <DefaultLayout>
      <div className="w-full flex flex-col items-center bg-gray-50 px-4 py-6 min-h-screen">
        <div className="w-full max-w-7xl flex flex-col items-center gap-8">
          <h1 className="text-3xl font-bold text-gray-800">Panel de Sensores</h1>

          {/* Tarjetas de métricas a tiempo real */}
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 w-full">
            {dataTypes.map((type, index) => {
              const latest = filteredData
                .filter((d) => d[type.key] !== null && d[type.key] !== undefined)
                .sort((a, b) => new Date(b.fecha_medicion || "").getTime() - new Date(a.fecha_medicion || "").getTime())[0];
              return (
                <motion.div
                  key={type.key}
                  className="bg-white rounded-lg shadow-md p-4 flex flex-col justify-center items-center h-32"
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.5, delay: index * 0.1 }}
                >
                  <p className="text-sm font-semibold text-gray-600 flex items-center gap-2">{type.icon} {type.nombre}</p>
                  <p
                    className="text-2xl font-bold mt-2"
                    style={{
                      color: type.key === "temperatura" ? "#dc2626" : type.key === "humedad_ambiente" ? "#2563eb" : type.key === "humedad_suelo" ? "#10b981" : type.key === "calidad_aire" ? "#f59e0b" : "#f59e0b",
                    }}
                  >
                    {latest && typeof latest[type.key] === "number"
                      ? latest[type.key].toFixed(type.decimals)
                      : "N/A"}{" "}
                    {type.key === "temperatura"
                      ? "°C"
                      : type.key === "humedad_ambiente" || type.key === "humedad_suelo"
                      ? "%"
                      : type.key === "calidad_aire"
                      ? "PPM"
                      : "lux"}
                  </p>
                </motion.div>
              );
            })}
          </div>

          {/* Tarjetas de promedios */}
          <SensorStats realTimeData={filteredData} selectedSensor="todos" />

          {/* Botones */}
          <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 w-full">
            <motion.button
              className="bg-white rounded-lg shadow-md p-2 flex items-center justify-center text-xs font-semibold text-gray-700 hover:bg-gray-100 h-16"
              onClick={() => publishCommand(sensorActive ? "STOP_SENSOR" : "START_SENSOR")}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              {sensorActive ? "Apagar Sensor" : "Encender Sensor"}
            </motion.button>
            <motion.button
              className="bg-white rounded-lg shadow-md p-2 flex items-center justify-center text-xs font-semibold text-gray-700 hover:bg-gray-100 h-16"
              onClick={() => publishCommand("RESTART_WIFI")}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              Reiniciar WiFi
            </motion.button>
            <GenerateReport realTimeData={filteredData} dataTypes={dataTypes} />
            <motion.button
              className="bg-green-600 text-white rounded-lg shadow-md p-2 flex items-center justify-center text-xs font-semibold h-16"
              onClick={() => navigate("/iot/datosmeteorologicos")}
              whileHover={{ scale: 1.05, backgroundColor: "#059669" }}
              whileTap={{ scale: 0.95 }}
            >
              Ver Datos Históricos
            </motion.button>
          </div>

          {/* Selectores */}
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
            <DataTypeSelector selectedDataType={selectedDataType} setSelectedDataType={debouncedSetSelectedDataType} />
            <ViewModeSelector selectedViewMode={selectedViewMode} setSelectedViewMode={setSelectedViewMode} />
          </div>

          {/* Gráficas o tabla */}
          <div className="w-full" ref={chartRef}>
            {selectedViewMode.id === "realtime" && chartRef.current ? (
              <SensorCharts realTimeData={filteredData} selectedDataType={selectedDataType} selectedSensor="todos" />
            ) : selectedViewMode.id === "realtime" ? (
              <p className="text-gray-600 text-center mt-4">No hay datos disponibles para renderizar el gráfico.</p>
            ) : (
              <div className="flex flex-col gap-6">
                <SensorTable realTimeData={filteredData} selectedDataType={selectedDataType} />
              </div>
            )}
          </div>
        </div>
      </div>
    </DefaultLayout>
  );
};

export default SensoresPage;