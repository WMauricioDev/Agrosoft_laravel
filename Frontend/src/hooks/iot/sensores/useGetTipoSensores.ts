import { useQuery } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { TipoSensor } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/tipo_sensores`;

const fetchTipoSensores = async (): Promise<TipoSensor[]> => {
  console.log("[useGetTipoSensores] Enviando GET a:", API_URL);
  try {
    const response = await api.get(API_URL);
    console.log("[useGetTipoSensores] Respuesta:", response.data);
    return response.data;
  } catch (error: any) {
    console.error("[useGetTipoSensores] Error en GET:", {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    const errorMessage =
      error.response?.data?.detail ||
      Object.entries(error.response?.data || {})
        .map(([key, value]) => `${key}: ${value}`)
        .join(", ") ||
      "Error al cargar los tipos de sensores";
    addToast({
      title: "Error",
      description: errorMessage,
      timeout: 3000,
      color: "danger",
    });
    throw error;
  }
};

export const useGetTipoSensores = () => {
  return useQuery<TipoSensor[], Error>({
    queryKey: ["tipoSensores"],
    queryFn: fetchTipoSensores,
    staleTime: 1000 * 60 * 10, // 10 minutos
    retry: 1,
    refetchOnWindowFocus: false,
  });
};