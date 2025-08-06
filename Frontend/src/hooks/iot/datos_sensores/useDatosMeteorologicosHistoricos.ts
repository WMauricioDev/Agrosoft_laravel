import { useQuery } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { SensorData } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/dato_meteorologicos`;

const fetchDatosHistoricos = async (): Promise<SensorData[]> => {
  console.log("[useDatosMeteorologicosHistoricos] Enviando GET a /api/dato_meteorologicos");
  try {
    const response = await api.get(API_URL);
    console.log("[useDatosMeteorologicosHistoricos] Respuesta de GET /api/dato_meteorologicos: ", response.data);

    return response.data.map((item: any) => ({
      id: item.id || 0,
      fk_sensor: item.fk_sensor || 0,
      sensor_nombre: item.sensor_nombre || "Desconocido",
      bancal_nombre: item.bancal_nombre || "N/A",
      temperatura: item.temperatura ? parseFloat(item.temperatura) : null,
      humedad_ambiente: item.humedad_ambiente ? parseFloat(item.humedad_ambiente) : null,
      fecha_medicion: item.fecha_medicion || "",
    }));
  } catch (error: any) {
    console.error("[useDatosMeteorologicosHistoricos] Error en GET /api/dato_meteorologicos: ", error);
    addToast({
      title: "Error",
      description: error.response?.data?.message || "Error al cargar los datos históricos",
      timeout: 3000,
      color: "danger",
    });
    throw error;
  }
};

export const useDatosMeteorologicosHistoricos = () => {
  return useQuery<SensorData[], Error>({
    queryKey: ["datosMeteorologicosHistoricos"],
    queryFn: fetchDatosHistoricos,
    staleTime: 1000 * 60,
  });
};