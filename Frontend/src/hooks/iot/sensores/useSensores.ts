import { useQuery } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { Sensor } from "@/types/iot/type";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/iot/sensores/`;

const fetchSensores = async (): Promise<Sensor[]> => {
  const navigate = useNavigate();
  const token = localStorage.getItem("access_token");
  console.log("Token actual:", token);

  try {
    console.log("[useSensores] Enviando GET a /iot/sensores/");
    const response = await api.get(API_URL, {
      headers: token ? { Authorization: `Bearer ${token}` } : {},
    });
    console.log("[useSensores] Respuesta de GET /iot/sensores/: ", response.data);
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
    console.error("[useSensores] Error en GET /iot/sensores/: ", {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    addToast({
      title: "Error",
      description: error.response?.data?.message || "Error al cargar los sensores. Verifica la configuración del servidor.",
      timeout: 3000,
      color: "danger",
    });
    if (error.message.includes("CORS") || error.message.includes("Network Error")) {
      addToast({
        title: "Error de CORS",
        description: "No se puede conectar al servidor debido a un problema de CORS. Contacta al administrador.",
        timeout: 5000,
        color: "danger",
      });
    } else if (!token) {
      navigate("/login");
    }
    throw error;
  }
};

export const useSensores = () => {
  const sensoresQuery = useQuery<Sensor[], Error>({
    queryKey: ["sensores"],
    queryFn: fetchSensores,
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