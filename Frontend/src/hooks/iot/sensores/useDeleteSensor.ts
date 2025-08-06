import { useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/sensors`;

const deleteSensor = async (id: number) => {
  console.log("[useDeleteSensor] Enviando DELETE a /api/sensors" + id + "/");
  try {
    const response = await api.delete(`${API_URL}${id}/`);
    console.log("[useDeleteSensor] Respuesta de DELETE /api/sensors" + id + "/: ", response.data);
    return response.data;
  } catch (error: any) {
    console.error("[useDeleteSensor] Error en DELETE /api/sensors" + id + "/: ", {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
    });
    throw new Error(error.response?.data?.message || "Error al eliminar el sensor");
  }
};

export const useDeleteSensor = () => {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: deleteSensor,
    onSuccess: () => {
      console.log("[useDeleteSensor] Sensor eliminado con éxito");
      queryClient.invalidateQueries({ queryKey: ["sensores"] });
      addToast({
        title: "Éxito",
        description: "Sensor eliminado con éxito",
        timeout: 3000,
        color: "success",
      });
    },
    onError: (error: any) => {
      console.error("[useDeleteSensor] Error al eliminar sensor: ", error);
      addToast({
        title: "Error",
        description: error.message || "Error al eliminar el sensor",
        timeout: 3000,
        color: "danger",
      });
    },
  });
};