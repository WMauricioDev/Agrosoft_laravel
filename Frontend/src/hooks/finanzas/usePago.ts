import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { Pago, CalculoPagoParams, PagoCreateParams } from "@/types/finanzas/Pago";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/pagos`;

const fetchPagos = async (): Promise<Pago[]> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");
  try {
    const response = await api.get(API_URL, {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data.pagos;
  } catch (error: any) {
    console.error("Error fetching pagos:", error.response?.data || error.message);
    throw error;
  }
};


const calcularPago = async (params: CalculoPagoParams): Promise<Pago> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");
  try {
    const response = await api.post(`${API_URL}/calcular`, params, {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data.data;
  } catch (error: any) {
    console.error("Error calculating pago:", error.response?.data || error.message);
    throw error;
  }
};

const crearPago = async (params: PagoCreateParams): Promise<Pago> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");
  try {
    const response = await api.post(API_URL, params, {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data.data;
  } catch (error: any) {
    console.error("Error creating pago:", error.response?.data || error.message);
    throw error;
  }
};

const eliminarPago = async (id: number) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) throw new Error("No se encontró el token de autenticación.");
  try {
    return await api.delete(`${API_URL}/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
  } catch (error: any) {
    console.error("Error deleting pago:", error.response?.data || error.message);
    throw error;
  }
};

export const usePagos = () => {
  return useQuery<Pago[], Error>({
    queryKey: ["pagos"],
    queryFn: fetchPagos,
  });
};

export const useCalcularPago = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: calcularPago,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["pagos"] });
      addToast({ 
        title: "Éxito", 
        description: "Pago calculado y registrado con éxito", 
        timeout: 3000,
        color: "success"
      });
    },
    onError: (error: any) => {
      console.error("CalcularPago error details:", error.response?.data || error.message);
      if (error.response?.status === 401) {
        addToast({
          title: "Sesión expirada",
          description: "Por favor, inicia sesión nuevamente.",
          timeout: 3000,
          color: "warning"
        });
      } else if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador",
          timeout: 3000,
          color: "warning"
        });
      } else {
        const errorMessage = error.response?.data?.errors 
          ? Array.isArray(error.response.data.errors) 
            ? error.response.data.errors.join(", ") 
            : error.response.data.errors 
          : "Error al calcular el pago";
        addToast({
          title: "Error",
          description: errorMessage,
          timeout: 3000,
          color: "danger"
        });
      }
    },
  });
};

export const useCrearPago = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: crearPago,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["pagos"] });
      addToast({ 
        title: "Éxito", 
        description: "Pago creado con éxito", 
        timeout: 3000,
        color: "success"
      });
    },
    onError: (error: any) => {
      console.error("CrearPago error details:", error.response?.data || error.message);
      if (error.response?.status === 401) {
        addToast({
          title: "Sesión expirada",
          description: "Por favor, inicia sesión nuevamente.",
          timeout: 3000,
          color: "warning"
        });
      } else if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador",
          timeout: 3000,
          color: "warning"
        });
      } else {
        const errorMessage = error.response?.data?.errors 
          ? Array.isArray(error.response.data.errors) 
            ? error.response.data.errors.join(", ") 
            : error.response.data.errors 
          : "Error al crear el pago";
        addToast({
          title: "Error",
          description: errorMessage,
          timeout: 3000,
          color: "danger"
        });
      }
    },
  });
};

export const useEliminarPago = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: eliminarPago,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["pagos"] });
      addToast({ 
        title: "Éxito", 
        description: "Pago eliminado con éxito", 
        timeout: 3000,
        color: "success"
      });
    },
    onError: (error: any) => {
      console.error("EliminarPago error details:", error.response?.data || error.message);
      if (error.response?.status === 401) {
        addToast({
          title: "Sesión expirada",
          description: "Por favor, inicia sesión nuevamente.",
          timeout: 3000,
          color: "warning"
        });
      } else if (error.response?.status === 403) {
        addToast({
          title: "Acceso denegado",
          description: "No tienes permiso para realizar esta acción, contacta a un administrador",
          timeout: 3000,
          color: "warning"
        });
      } else {
        const errorMessage = error.response?.data?.errors 
          ? Array.isArray(error.response.data.errors) 
            ? error.response.data.errors.join(", ") 
            : error.response.data.errors 
          : "Error al eliminar el pago";
        addToast({
          title: "Error",
          description: errorMessage,
          timeout: 3000,
          color: "danger"
        });
      }
    },
  });
};