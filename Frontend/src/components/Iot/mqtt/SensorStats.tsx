import { useMemo } from "react";
import { motion } from "framer-motion";
import { SensorStatsProps } from "@/types/iot/iotmqtt";

export const SensorStats: React.FC<SensorStatsProps> = ({ realTimeData, selectedSensor = "todos" }) => {
  const stats = useMemo(() => {
    const filteredData = selectedSensor === "todos" ? realTimeData : realTimeData.filter((d) => d.device_code === selectedSensor.toString());
    const tempValues = filteredData
      .filter((d) => d.temperatura !== null && d.temperatura !== undefined)
      .map((d) => d.temperatura as number);
    const humValues = filteredData
      .filter((d) => d.humedad_ambiente !== null && d.humedad_ambiente !== undefined)
      .map((d) => d.humedad_ambiente as number);
    const soilValues = filteredData
      .filter((d) => d.humedad_suelo !== null && d.humedad_suelo !== undefined)
      .map((d) => d.humedad_suelo as number);
    const airValues = filteredData
      .filter((d) => d.calidad_aire !== null && d.calidad_aire !== undefined)
      .map((d) => d.calidad_aire as number);
    const luxValues = filteredData
      .filter((d) => d.luminosidad !== null && d.luminosidad !== undefined)
      .map((d) => d.luminosidad as number);

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

  return (
    <div className="grid grid-cols-1 md:grid-cols-5 gap-6 w-full">
      {[
        { title: "Temperatura (°C)", stats: stats.temp, decimals: 2, color: "text-red-500" },
        { title: "Humedad Ambiente (%)", stats: stats.hum, decimals: 1, color: "text-blue-600" },
        { title: "Humedad Suelo (%)", stats: stats.soil, decimals: 1, color: "text-green-500" },
        { title: "Calidad Aire (PPM)", stats: stats.air, decimals: 0, color: "text-yellow-500" },
        { title: "Luminosidad (lux)", stats: stats.lux, decimals: 0, color: "text-amber-500" },
      ].map((item, index) => (
        <motion.div
          key={index}
          className="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between items-center"
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 0.5, delay: index * 0.1 }}
        >
          <h3 className={`text-sm font-semibold ${item.color}`}>{item.title}</h3>
          <div className="text-center">
            <p>Max: {item.stats.max?.toFixed(item.decimals) ?? "N/A"}</p>
            <p>Min: {item.stats.min?.toFixed(item.decimals) ?? "N/A"}</p>
            <p>Promedio: {item.stats.avg?.toFixed(item.decimals) ?? "N/A"}</p>
          </div>
        </motion.div>
      ))}
    </div>
  );
};

export default SensorStats;