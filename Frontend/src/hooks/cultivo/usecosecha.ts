import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { Cosecha } from "@/types/cultivo/Cosecha";
const BASE_URL = import.meta.env.VITE_API_BASE_URL;

const API_URL = `${BASE_URL}/api/cosechas/`;

const fetchCosechas = async (): Promise<Cosecha[]> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");
  const response = await api.get(API_URL, {
    headers: { Authorization: `Bearer ${token}` },
  });
  return response.data;
};

const registrarCosecha = async (cosecha: Cosecha) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");

  const payload = {
    cultivo_id: cosecha.cultivo_id,
    cantidad: cosecha.cantidad,
    unidad_medida_id: cosecha.unidad_medida_id,
    fecha: cosecha.fecha, // Se espera que ya esté en formato "yyyy-MM-dd"
  };

  return api.post(API_URL, payload, {
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
    },
  });
};

const actualizarCosecha = async (id: number, cosecha: Cosecha) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");

  const payload = {
    cultivo_id: cosecha.cultivo_id,
    cantidad: cosecha.cantidad,
    unidad_medida_id: cosecha.unidad_medida_id,
    fecha: cosecha.fecha, // Asegúrate de que esté en formato "yyyy-MM-dd"
  };

  console.log("Payload enviado al servidor:", payload); // Para depuración

  return api.put(`${API_URL}${id}/`, payload, {
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
    },
  });
};

const eliminarCosecha = async (id: number) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");

  return api.delete(`${API_URL}${id}/`, {
    headers: { Authorization: `Bearer ${token}` },
  });
};

export const useCosechas = () => {
  return useQuery<Cosecha[], Error>({
    queryKey: ["cosechas"],
    queryFn: fetchCosechas,
  });
};

export const useRegistrarCosecha = () => {
  return useMutation({
    mutationFn: registrarCosecha,
    onSuccess: () => {
      addToast({
        title: "Éxito",
        description: "Cosecha registrada con éxito",
        timeout: 3000,
        color: "success",
      });
    },
    onError: (error: any) => {
      if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador.",
          timeout: 3000,
          color: "warning",
        });
      } else {
        addToast({
          title: "Error",
          description: "Error al registrar la cosecha",
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};

export const useActualizarCosecha = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: ({ id, cosecha }: { id: number; cosecha: Cosecha }) =>
      actualizarCosecha(id, cosecha),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["cosechas"] });
      addToast({
        title: "Éxito",
        description: "Cosecha actualizada con éxito",
        timeout: 3000,
        color: "success",
      });
    },
    onError: (error: any) => {
      console.error("Error del servidor:", error.response?.data); // Para depuración
      if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador.",
          timeout: 3000,
          color: "warning",
        });
      } else {
        addToast({
          title: "Error",
          description: `Error al actualizar la cosecha: ${error.response?.data?.detail || error.message}`,
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};

export const useEliminarCosecha = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number) => eliminarCosecha(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["cosechas"] });
      addToast({
        title: "Éxito",
        description: "Cosecha eliminada con éxito",
        timeout: 3000,
        color: "success",
      });
    },
    onError: (error: any) => {
      if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador.",
          timeout: 3000,
          color: "warning",
        });
      } else {
        addToast({
          title: "Error",
          description: "Error al eliminar la cosecha",
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};