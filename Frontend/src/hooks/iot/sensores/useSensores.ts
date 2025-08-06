import { useQuery } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { Sensor } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/sensors`;

const fetchSensores = async (): Promise<Sensor[]> => {
  console.log("[useSensores] Enviando GET a /api/sensors");
  try {
    const response = await api.get(API_URL);
    console.log("[useSensores] Respuesta de GET /api/sensors: ", response.data);
    return response.data.map((sensor: any) => ({
      id: sensor.id || 0,
      nombre: sensor.nombre || "Sin nombre",
      tipo_sensor: sensor.tipo_sensor_nombre || "Desconocido",
      tipo_sensor_id: sensor.tipo_sensor_id || 0,
      unidad_medida: sensor.unidad_medida || "",
      descripcion: sensor.descripcion || "",
      estado: sensor.estado || "inactivo",
      medida_minima: parseFloat(sensor.medida_minima) || 0,
      medida_maxima: parseFloat(sensor.medida_maxima) || 0,
      device_code: sensor.device_code || null,
      bancal_id: sensor.bancal_id || null,
      bancal_nombre: sensor.bancal_nombre || "Sin bancal",
    }));
  } catch (error: any) {
    console.error("[useSensores] Error en GET /api/sensors: ", {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    let errorMessage = error.response?.data?.message || "Error al cargar los sensores";
    if (error.message.includes("CORS") || error.message.includes("Network Error")) {
      errorMessage = "No se puede conectar al servidor debido a un problema de CORS. Contacta al administrador.";
    }
    addToast({
      title: "Error",
      description: errorMessage,
      timeout: 3000,
      color: "danger",
    });
    throw error;
  }
};

export const useSensores = () => {
  const sensoresQuery = useQuery<Sensor[], Error>({
    queryKey: ["sensores"],
    queryFn: fetchSensores,
    staleTime: 1000 * 60,
  });

  console.log("[useSensores] Estado actual: ", {
    sensores: sensoresQuery.data,
    isLoading: sensoresQuery.isLoading,
    error: sensoresQuery.error,
  });

  return {
    sensores: sensoresQuery.data || [],
    isLoading: sensoresQuery.isLoading,
    error: sensoresQuery.error,
  };
};