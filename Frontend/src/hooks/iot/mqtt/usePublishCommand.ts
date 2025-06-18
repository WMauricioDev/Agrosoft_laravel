import { addToast } from "@heroui/react";
import mqtt from "mqtt";

export const usePublishCommand = (
  mqttClient: mqtt.MqttClient | null,
 
  _sensorActive: boolean,
  setSensorActive: (active: boolean) => void
) => {
  const publishCommand = (command: string) => {
    if (!mqttClient || !mqttClient.connected) {
      addToast({
        title: "Error",
        description: "No hay conexión MQTT activa",
        timeout: 3000,
        color: "danger",
      });
      return;
    }

    mqttClient.publish("sensor/control/command", command, { qos: 1 }, (err) => {
      if (err) {
        addToast({
          title: "Error",
          description: `No se pudo enviar el comando: ${err.message}`,
          timeout: 3000,
          color: "danger",
        });
      } else {
        addToast({
          title: "Éxito",
          description: `Comando ${command} enviado`,
          timeout: 3000,
          color: "success",
        });
        if (command === "STOP_SENSOR") {
          setSensorActive(false);
        } else if (command === "START_SENSOR") {
          setSensorActive(true);
        }
      }
    });
  };

  return publishCommand;
};

export default usePublishCommand;