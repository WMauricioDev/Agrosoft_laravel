import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios";
import { addToast } from "@heroui/react";
import { ActividadForm } from "@/types/cultivo/Actividad";
import { Insumo } from "@/types/inventario/Insumo";
import { User } from "@/context/AuthContext";

const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/actividades/`;

const fetchActividades = async (): Promise<ActividadForm[]> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  const response = await api.get(API_URL, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};

export const useActividades = () => {
  return useQuery<ActividadForm[], Error>({
    queryKey: ["actividades"],
    queryFn: fetchActividades,
  });
};

const fetchUsuarios = async (): Promise<User[]> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  const response = await api.get(`${BASE_URL}/api/user`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};

export const useUsuarios = () => {
  return useQuery<User[], Error>({
    queryKey: ["usuarios"],
    queryFn: fetchUsuarios,
  });
};

const fetchInsumos = async (): Promise<Insumo[]> => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  const response = await api.get(`${BASE_URL}/api/insumos`, {
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
  return response.data;
};

export const useInsumos = () => {
  return useQuery<Insumo[], Error>({
    queryKey: ["insumos"],
    queryFn: fetchInsumos,
  });
};

const registrarActividad = async (actividad: ActividadForm) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  try {
    console.log("Datos enviados a la API:", actividad);
    const response = await api.post(API_URL, {
      tipo_actividad_id: actividad.tipo_actividad_id,
      descripcion: actividad.descripcion,
      fecha_inicio: actividad.fecha_inicio,
      fecha_fin: actividad.fecha_fin,
      cultivo_id: actividad.cultivo_id,
      estado: actividad.estado,
      prioridad: actividad.prioridad,
      instrucciones_adicionales: actividad.instrucciones_adicionales,
      usuarios: actividad.usuarios,
      insumos: actividad.insumos?.map((i) => ({
        insumo_id: i.insumo_id,
        cantidad_usada: i.cantidad_usada,
      })),
      herramientas: actividad.herramientas?.map((h) => ({
        herramienta_id: h.herramienta_id,
        cantidad_entregada: h.cantidad_entregada,
        entregada: h.entregada ?? true,
        devuelta: h.devuelta ?? false,
        fecha_devolucion: h.fecha_devolucion ?? null,
      })),
    }, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data;
  } catch (error: any) {
    console.error("Error en la API:", {
      mensaje: error.message,
      status: error.response?.status,
      data: error.response?.data,
      headers: error.response?.headers,
    });
    throw error;
  }
};

const actualizarActividad = async (id: number, actividad: ActividadForm) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  if (actividad.estado === 'COMPLETADA') {
    throw new Error("Use el método específico para finalizar actividades");
  }

  try {
    console.log("Actividad enviada para actualizar:", actividad);
    const response = await api.put(`${API_URL}${id}`, {
      tipo_actividad_id: actividad.tipo_actividad_id,
      descripcion: actividad.descripcion,
      fecha_inicio: actividad.fecha_inicio,
      fecha_fin: actividad.fecha_fin,
      cultivo_id: actividad.cultivo_id,
      estado: actividad.estado,
      prioridad: actividad.prioridad,
      instrucciones_adicionales: actividad.instrucciones_adicionales,
      usuarios: actividad.usuarios,
      insumos: actividad.insumos?.map((i) => ({
        insumo_id: i.insumo_id,
        cantidad_usada: i.cantidad_usada,
      })),
      herramientas: actividad.herramientas?.map((h) => ({
        herramienta_id: h.herramienta_id,
        cantidad_entregada: h.cantidad_entregada,
        entregada: h.entregada ?? true,
        devuelta: h.devuelta ?? false,
        fecha_devolucion: h.fecha_devolucion ?? null,
      })),
    }, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    });
    return response.data;
  } catch (error: any) {
    console.error("Error en la API:", error.response?.data);
    throw error;
  }
};

// Delete an activity
const eliminarActividad = async (id: number) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  return api.delete(`${API_URL}${id}`, {
    headers: { Authorization: `Bearer ${token}` },
  });
};

// Finalize an activity
const finalizarActividad = async (id: number, fecha_fin: string) => {
  const token = localStorage.getItem("accesso_token");
  if (!token) {
    throw new Error("No se encontró el token de autenticación.");
  }

  try {
    console.log("Payload sent:", { fecha_fin });
    const response = await api.post(`${API_URL}${id}/finalizar`, { fecha_fin }, {
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/json",
      },
    });
    return response.data;
  } catch (error: any) {
    console.error("Error en la API:", error.response?.data);
    throw error;
  }
};

// Hook to create an activity
export const useRegistrarActividad = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (actividad: ActividadForm) => registrarActividad(actividad),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["actividades"] });
      addToast({
        title: "Éxito",
        description: "Actividad registrada con éxito",
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
          description: `Error al registrar la actividad: ${error.response?.data?.error || "Unknown error"}`,
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};

// Hook to update an activity
export const useActualizarActividad = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: ({ id, actividad }: { id: number; actividad: ActividadForm }) => actualizarActividad(id, actividad),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["actividades"] });
      addToast({
        title: "Éxito",
        description: "Actividad actualizada con éxito",
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
          description: `Error al actualizar la actividad: ${error.response?.data?.error || "Unknown error"}`,
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};

// Hook to delete an activity
export const useEliminarActividad = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: (id: number) => eliminarActividad(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["actividades"] });
      addToast({
        title: "Éxito",
        description: "Actividad eliminada con éxito",
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
          description: `Error al eliminar la actividad: ${error.response?.data?.error || "Unknown error"}`,
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};

// Hook to finalize an activity
export const useFinalizarActividad = () => {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: ({ id, fecha_fin }: { id: number; fecha_fin: string }) => finalizarActividad(id, fecha_fin),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["actividades"] });
      addToast({
        title: "Éxito",
        description: "Actividad finalizada con éxito",
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
          description: `Error al finalizar la actividad: ${error.response?.data?.error || "Unknown error"}`,
          timeout: 3000,
          color: "danger",
        });
      }
    },
  });
};