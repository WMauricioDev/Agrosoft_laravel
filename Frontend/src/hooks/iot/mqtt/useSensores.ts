import { useEffect, useState, useRef } from "react";
import { subscribeToMqtt } from "@/components/utils/mqttClient";
import { SensorData } from "@/types/iot/iotmqtt";
import { addToast } from "@heroui/react";
import mqtt from "mqtt";

export const useSensores = () => {
  const [realTimeData, setRealTimeData] = useState<SensorData[]>([]);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [mqttClient, setMqttClient] = useState<mqtt.MqttClient | null>(null);
  const lastDataTimestampRef = useRef<number | null>(null);
  const DATA_TIMEOUT = 15000; 

  useEffect(() => {
    const client = mqtt.connect("wss://92ae5e18dc884fefa81c4f3580a7485b.s1.eu.hivemq.cloud:8884/mqtt", {
      username: "agrosoft",
      password: "Agrosoft2025!",
    });

    client.on("connect", () => {
      console.log("Conectado a MQTT para publicar");
      setMqttClient(client);
    });

    client.on("error", (err) => {
      console.error("Error MQTT:", err);
      addToast({
        title: "Error",
        description: "No se pudo conectar al broker MQTT",
        timeout: 3000,
        color: "danger",
      });
      setError("No se pudo conectar al broker MQTT");
    });

    return () => {
      client.end();
    };
  }, []);

  useEffect(() => {
    const unsubscribe = subscribeToMqtt(({ realTimeData, isConnected, error }) => {
      console.log("Datos MQTT recibidos:", realTimeData);
      setRealTimeData(realTimeData);
      setIsLoading(!isConnected && !error);
      setError(error);
      lastDataTimestampRef.current = Date.now(); // Actualizar timestamp al recibir datos
    });

    // Intervalo para verificar si han pasado 15 segundos sin datos
    const timeoutCheck = setInterval(() => {
      if (lastDataTimestampRef.current && Date.now() - lastDataTimestampRef.current > DATA_TIMEOUT) {
        setRealTimeData([]); // Limpiar datos si no llegan en 15 segundos
        console.warn("No se han recibido datos en 15 segundos, limpiando tarjetas.");
      }
    }, 1000); // Revisar cada segundo

    return () => {
      unsubscribe();
      clearInterval(timeoutCheck);
    };
  }, []);

  return { realTimeData, isLoading, error, mqttClient };
};