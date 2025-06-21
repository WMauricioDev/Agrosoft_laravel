import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { motion } from "framer-motion";
import { FaTemperatureHigh, FaTint, FaLeaf, FaWind, FaLightbulb } from "react-icons/fa";
import DefaultLayout from "@/layouts/default";
import { useSensores } from "@/hooks/iot/mqtt/useSensores";
import { usePublishCommand } from "@/hooks/iot/mqtt/usePublishCommand";
import { DataTypeSelector } from "@/components/Iot/mqtt/DataTypeSelector";
import { ViewModeSelector } from "@/components/Iot/mqtt/ViewModeSelector";
import { SensorStats } from "@/components/Iot/mqtt/SensorStats";
import { SensorCharts } from "@/components/Iot/mqtt/SensorCharts";
import { SensorTable } from "@/components/Iot/mqtt/SensorTable";
import { DataType, ViewMode } from "@/types/iot/iotmqtt";

const dataTypes: DataType[] = [
  {
    label: "Temperatura (°C)",
    key: "temperatura",
    icon: <FaTemperatureHigh className="text-red-500" />,
    tipo_sensor: "temperatura",
    decimals: 2,
  },
  {
    label: "Humedad Ambiente (%)",
    key: "humedad_ambiente",
    icon: <FaTint className="text-blue-500" />,
    tipo_sensor: "humedad_ambiente",
    decimals: 1,
  },
  {
    label: "Humedad Suelo (%)",
    key: "humedad_suelo",
    icon: <FaLeaf className="text-green-500" />,
    tipo_sensor: "humedad_suelo",
    decimals: 1,
  },
  {
    label: "Calidad Aire (PPM)",
    key: "calidad_aire",
    icon: <FaWind className="text-yellow-500" />,
    tipo_sensor: "calidad_aire",
    decimals: 0,
  },
  {
    label: "Luminosidad (lux)",
    key: "luminosidad",
    icon: <FaLightbulb className="text-amber-500" />,
    tipo_sensor: "luminosidad",
    decimals: 0,
  },
];

const viewModes: ViewMode[] = [
  { id: "realtime", label: "Tiempo Real" },
  { id: "allData", label: "Todos los Datos" },
];

const SensoresPage: React.FC = () => {
  const { realTimeData, isLoading, error, mqttClient } = useSensores();
  const [sensorActive, setSensorActive] = useState(true);
  const [selectedDataType, setSelectedDataType] = useState(dataTypes[0]);
  const [selectedViewMode, setSelectedViewMode] = useState(viewModes[0]);
  const publishCommand = usePublishCommand(mqttClient, sensorActive, setSensorActive);
  const navigate = useNavigate();

  const filteredData = realTimeData.filter((d) => d.device_code === "ESP32_001");

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
      <div className="w-full flex flex-col items-center bg-gray-50 px-3 py-2 min-h-screen">
        <div className="w-full max-w-6xl flex flex-col items-center gap-2">
          <h1 className="text-2xl font-bold text-gray-800 mb-4">Datos de Sensores</h1>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-4">
            {dataTypes.map((type, index) => {
              const latest = filteredData
                .filter((d) => d[type.key] !== null && d[type.key] !== undefined)
                .sort((a, b) => new Date(b.fecha_medicion || "").getTime() - new Date(a.fecha_medicion || "").getTime())[0];

              return (
                <motion.div
                  key={type.key}
                  className="bg-white rounded-xl shadow-md p-4 text-center w-full sm:w-56 h-32 flex flex-col justify-center items-center"
                  initial={{ opacity: 0, y: 20 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.5, delay: index * 0.1 }}
                >
                  <p className="text-base font-semibold text-gray-700 flex items-center justify-center gap-2">
                    {type.icon} {type.label}
                  </p>
                  <p
                    className="text-3xl font-bold mt-2"
                    style={{
                      color:
                        type.key === "temperatura"
                          ? "#dc2626"
                          : type.key === "humedad_ambiente"
                          ? "#2563eb"
                          : type.key === "humedad_suelo"
                          ? "#10b981"
                          : type.key === "calidad_aire"
                          ? "#f59e0b"
                          : "#f59e0b",
                    }}
                  >
                    {latest?.[type.key] !== null && latest?.[type.key] !== undefined && typeof latest?.[type.key] === "number"
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
            <motion.button
              className="bg-green-600 rounded-xl shadow-md p-4 text-center w-full sm:w-56 h-32 flex flex-col justify-center items-center text-white text-sm font-semibold"
              onClick={() => navigate("/iot/datosmeteorologicos")}
              whileHover={{ scale: 1.05, backgroundColor: "#059669" }}
              whileTap={{ scale: 0.95 }}
            >
              Ver Datos Históricos
            </motion.button>
          </div>

          <div className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-4">
            <DataTypeSelector selectedDataType={selectedDataType} setSelectedDataType={setSelectedDataType} />
            <ViewModeSelector selectedViewMode={selectedViewMode} setSelectedViewMode={setSelectedViewMode} />
          </div>

          <div className="flex flex-wrap gap-2 justify-center mb-4">
            <motion.button
              className={`px-3 py-1 text-white text-xs font-semibold rounded-lg shadow-sm hover:shadow-md ${
                sensorActive ? "bg-red-600 hover:bg-red-700" : "bg-blue-600 hover:bg-blue-700"
              }`}
              onClick={() => publishCommand(sensorActive ? "STOP_SENSOR" : "START_SENSOR")}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              {sensorActive ? "Apagar Sensor" : "Encender Sensor"}
            </motion.button>
            <motion.button
              className="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-lg shadow-sm hover:shadow-md hover:bg-blue-700"
              onClick={() => publishCommand("RESTART_WIFI")}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
            >
              Reiniciar WiFi
            </motion.button>
          </div>

          <div className="w-full h-80">
            {selectedViewMode.id === "realtime" ? (
              <SensorCharts realTimeData={filteredData} selectedDataType={selectedDataType} selectedSensor="todos" />
            ) : (
              <div className="flex flex-col gap-2">
                <SensorStats realTimeData={filteredData} selectedSensor="todos" />
                <div className="max-h-48 overflow-y-auto">
                  <SensorTable realTimeData={filteredData} />
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </DefaultLayout>
  );
};

export default SensoresPage;