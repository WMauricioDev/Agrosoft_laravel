import { useQuery } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { SensorData } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/iot/datosmeteorologicos/`;

const fetchDatosHistoricos = async (): Promise<SensorData[]> => {
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
    console.log("[useDatosMeteorologicosHistoricos] Enviando GET a /iot/datosmeteorologicos/");
    const response = await api.get(API_URL, {
      headers: { Authorization: `Bearer ${token}` },
    });

    console.log("[useDatosMeteorologicosHistoricos] Respuesta de GET /iot/datosmeteorologicos/: ", response.data);

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
    console.error("[useDatosMeteorologicosHistoricos] Error en GET /iot/datosmeteorologicos/: ", error);
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