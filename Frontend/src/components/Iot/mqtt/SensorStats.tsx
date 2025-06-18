import { useMemo } from "react";
import { motion } from "framer-motion";
import { SensorStatsProps } from "@/types/iot/iotmqtt";

export const SensorStats: React.FC<SensorStatsProps> = ({ realTimeData, selectedSensor = "todos" }) => {
  const stats = useMemo(() => {
    const filteredData = selectedSensor === "todos" ? realTimeData : realTimeData.filter((d) => d.device_code === selectedSensor.toString());
    const tempValues = filteredData
      .filter((d) => d.temperatura != null)
      .map((d) => (typeof d.temperatura === "number" ? d.temperatura : parseFloat(d.temperatura as string) || 0));
    const humValues = filteredData
      .filter((d) => d.humedad_ambiente != null)
      .map((d) => (typeof d.humedad_ambiente === "number" ? d.humedad_ambiente : parseFloat(d.humedad_ambiente as string) || 0));
    const soilValues = filteredData
      .filter((d) => d.humedad_suelo != null)
      .map((d) => (typeof d.humedad_suelo === "number" ? d.humedad_suelo : parseFloat(d.humedad_suelo as string) || 0));
    const airValues = filteredData
      .filter((d) => d.calidad_aire != null)
      .map((d) => (typeof d.calidad_aire === "number" ? d.calidad_aire : parseFloat(d.calidad_aire as string) || 0));
    const luxValues = filteredData
      .filter((d) => d.luminosidad != null)
      .map((d) => (typeof d.luminosidad === "number" ? d.luminosidad : parseFloat(d.luminosidad as string) || 0));

    return {
      temp: {
        max: tempValues.length ? Math.max(...tempValues) : null,
        min: tempValues.length ? Math.min(...tempValues) : null,
        avg: tempValues.length ? tempValues.reduce((a, b) => a + b, 0) / tempValues.length : null,
      },
      hum: {
        max: humValues.length ? Math.max(...humValues) : null,
        min: humValues.length ? Math.min(...humValues) : null,
        avg: humValues.length ? humValues.reduce((a, b) => a + b, 0) / humValues.length : null,
      },
      soil: {
        max: soilValues.length ? Math.max(...soilValues) : null,
        min: soilValues.length ? Math.min(...soilValues) : null,
        avg: soilValues.length ? soilValues.reduce((a, b) => a + b, 0) / soilValues.length : null,
      },
      air: {
        max: airValues.length ? Math.max(...airValues) : null,
        min: airValues.length ? Math.min(...airValues) : null,
        avg: airValues.length ? airValues.reduce((a, b) => a + b, 0) / airValues.length : null,
      },
      lux: {
        max: luxValues.length ? Math.max(...luxValues) : null,
        min: luxValues.length ? Math.min(...luxValues) : null,
        avg: luxValues.length ? luxValues.reduce((a, b) => a + b, 0) / luxValues.length : null,
      },
    };
  }, [realTimeData, selectedSensor]);

  console.log("stats:", stats);

  return (
    <motion.div
      className="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4"
      initial={{ opacity: 0, y: 20 }}
      animate={{ opacity: 1, y: 0 }}
      transition={{ duration: 0.5 }}
    >
      <div>
        <h3 className="text-lg font-semibold text-gray-700">Temperatura (°C)</h3>
        <p>Max: {stats.temp.max?.toFixed(2) ?? "N/A"}</p>
        <p>Min: {stats.temp.min?.toFixed(2) ?? "N/A"}</p>
        <p>Promedio: {stats.temp.avg?.toFixed(2) ?? "N/A"}</p>
      </div>
      <div>
        <h3 className="text-lg font-semibold text-gray-700">Humedad Ambiente (%)</h3>
        <p>Max: {stats.hum.max?.toFixed(1) ?? "N/A"}</p>
        <p>Min: {stats.hum.min?.toFixed(1) ?? "N/A"}</p>
        <p>Promedio: {stats.hum.avg?.toFixed(1) ?? "N/A"}</p>
      </div>
      <div>
        <h3 className="text-lg font-semibold text-gray-700">Humedad Suelo (%)</h3>
        <p>Max: {stats.soil.max?.toFixed(1) ?? "N/A"}</p>
        <p>Min: {stats.soil.min?.toFixed(1) ?? "N/A"}</p>
        <p>Promedio: {stats.soil.avg?.toFixed(1) ?? "N/A"}</p>
      </div>
      <div>
        <h3 className="text-lg font-semibold text-gray-700">Calidad Aire (PPM)</h3>
        <p>Max: {stats.air.max?.toFixed(0) ?? "N/A"}</p>
        <p>Min: {stats.air.min?.toFixed(0) ?? "N/A"}</p>
        <p>Promedio: {stats.air.avg?.toFixed(0) ?? "N/A"}</p>
      </div>
      <div>
        <h3 className="text-lg font-semibold text-gray-700">Luminosidad (lux)</h3>
        <p>Max: {stats.lux.max?.toFixed(0) ?? "N/A"}</p>
        <p>Min: {stats.lux.min?.toFixed(0) ?? "N/A"}</p>
        <p>Promedio: {stats.lux.avg?.toFixed(0) ?? "N/A"}</p>
      </div>
    </motion.div>
  );
};

export default SensorStats;