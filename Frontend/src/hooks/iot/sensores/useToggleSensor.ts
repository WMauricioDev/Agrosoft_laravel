import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/sensors`;

const toggleSensor = async ({ sensorId, activo }: { sensorId: number; activo: boolean }) => {
  console.log("[useToggleSensor] Enviando PATCH a /api/sensors" + sensorId + "/");
  try {
    const response = await api.patch(`${API_URL}${sensorId}/`, {
      estado: activo ? "activo" : "inactivo",
    });
    console.log("[useToggleSensor] Respuesta de PATCH /api/sensors" + sensorId + "/: ", response.data);
    return response.data;
  } catch (error: any) {
    console.error("[useToggleSensor] Error al cambiar el estado del sensor:", {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    throw new Error(error.response?.data?.message || "Error al cambiar el estado del sensor");
  }
};

export const useToggleSensor = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: toggleSensor,
    onSuccess: () => {
      console.log("[useToggleSensor] Estado del sensor actualizado con éxito");
      queryClient.invalidateQueries({ queryKey: ["sensores"] });
      addToast({
        title: "Éxito",
        description: "Estado del sensor actualizado con éxito",
        timeout: 3000,
        color: "success",
      });
    },
    onError: (error: any) => {
      console.error("[useToggleSensor] Error al cambiar estado del sensor: ", error);
      addToast({
        title: "Error",
        description: error.message || "Error al cambiar el estado del sensor",
        timeout: 3000,
        color: "danger",
      });
    },
  });
};