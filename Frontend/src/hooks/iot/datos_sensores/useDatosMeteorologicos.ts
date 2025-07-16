import { useQuery } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { SensorData } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/iot/datosmeteorologicos/`;

const fetchDatosMeteorologicos = async (sensorId: number): Promise<SensorData[]> => {
  const token = localStorage.getItem("access_token");
  if (!token) {
    addToast({
      title: "Sesión expirada",
      description: "No se encontró el token de autenticación, por favor inicia sesión nuevamente.",
      timeout: 3000,
      color: "danger",
    });
    throw new Error("No se encontró el token de autenticación.");
  }

  try {
    console.log(`Fetching data for sensor ${sensorId}`);
    const response = await api.get(API_URL, {
      params: { fk_sensor_id: sensorId },
      headers: { Authorization: `Bearer ${token}` },
    });
    console.log("Response from /iot/datosmeteorologicos/:", response.data);
    return response.data.map((item: any) => ({
      id: item.id,
      fk_sensor: item.fk_sensor,
      temperatura: item.temperatura || null,
      humedad_ambiente: item.humedad_ambiente || null,
      fecha_medicion: item.fecha_medicion,
    }));
  } catch (error: any) {
    console.error("Error fetching data for sensor:", error);
    addToast({
      title: "Error",
      description: error.response?.data?.message || "Error al cargar los datos meteorológicos",
      timeout: 3000,
      color: "danger",
    });
    throw error;
  }
};

export const useDatosMeteorologicos = (sensorId: number) => {
  return useQuery<SensorData[], Error>({
    queryKey: ["datosMeteorologicos", sensorId],
    queryFn: () => fetchDatosMeteorologicos(sensorId),
    enabled: !!sensorId,
    staleTime: 1000 * 60,
  });
};