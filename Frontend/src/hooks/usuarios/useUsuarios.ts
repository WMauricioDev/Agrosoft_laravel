import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import api from "@/components/utils/axios"; 
import { addToast } from "@heroui/react";


const BASE_URL = import.meta.env.VITE_API_BASE_URL;
const API_URL = `${BASE_URL}/api/`;


export interface Rol {
   id: number;
  nombre: string;
  created_at: string | null;
  updated_at: string | null;
}

export interface Usuario {
  id: number;
  nombre: string;
  apellido: string;
  email: string;
  numero_documento: number;
  rol: Rol | null;
}

export interface UsuarioUpdate {
  id: number;
  nombre: string;
  apellido: string;
  email: string;
  numero_documento: number;
  rol: number | null;   
}

export const useUsuarios = () => {
  const queryClient = useQueryClient();

  const fetchUsuarios = async (): Promise<Usuario[]> => {
    const token = localStorage.getItem("accesso_token");
    if (!token) throw new Error("No se encontró el token de autenticación.");
    const response = await api.get(`${API_URL}user/`, {
      headers: { Authorization: `Bearer ${token}`, "Content-Type": "application/json" },
    });
    if (!Array.isArray(response.data)) throw new Error("La API no devolvió un array de usuarios.");
    return response.data;
  };

  
  const fetchRoles = async (): Promise<Rol[]> => {
    const token = localStorage.getItem("accesso_token");
    if (!token) throw new Error("No se encontró el token de autenticación.");
    const response = await api.get(`${API_URL}roles/`, {
      headers: { Authorization: `Bearer ${token}`, "Content-Type": "application/json" },
    });

    if (!Array.isArray(response.data.data)) throw new Error("La API no devolvió un array de roles.");
    return response.data.data;
  };

  const updateUsuario = async (usuario: UsuarioUpdate): Promise<Usuario> => {
    const token = localStorage.getItem("accesso_token");
    if (!token) throw new Error("No se encontró el token de autenticación.");
    const response = await api.patch(`${API_URL}user/${usuario.id}/`, usuario, {
      headers: { Authorization: `Bearer ${token}`, "Content-Type": "application/json" },
    });
    return response.data; 
  };
  const deleteUsuario = async (id: number): Promise<void> => {
    const token = localStorage.getItem("accesso_token");
    if (!token) throw new Error("No se encontró el token de autenticación.");
    await api.delete(`${API_URL}user/${id}/`, {
      headers: { Authorization: `Bearer ${token}` },
    });
  };

  const usuariosQuery = useQuery<Usuario[], Error>({
    queryKey: ["usuarios"],
    queryFn: fetchUsuarios,
    retry: 1,
    enabled: !!localStorage.getItem("accesso_token"),
  });

  const rolesQuery = useQuery<Rol[], Error>({
    queryKey: ["roles"],
    queryFn: fetchRoles,
    retry: 1,
    enabled: !!localStorage.getItem("accesso_token"),
  });

const updateMutation = useMutation<Usuario, Error, UsuarioUpdate>({
  mutationFn: updateUsuario,
  onSuccess: (updatedUsuario) => {
    queryClient.setQueryData<Usuario[]>(["usuarios"], (oldData) =>
      oldData
        ? oldData.map((u) => (u.id === updatedUsuario.id ? updatedUsuario : u))
        : [updatedUsuario]
    );
    queryClient.invalidateQueries({ queryKey: ["usuarios"] });
    addToast({
      title: "Éxito",
      description: "Usuario actualizado con éxito",
      timeout: 3000,
      color: "success",
    });
  },
  onError: (error: any) => {
    const response = error?.response;

    if (response?.status === 403) {
      const mensaje =
        response?.data?.message || "No tienes permiso para editar este usuario";
      addToast({
        title: "Acción no permitida",
        description: mensaje,
        timeout: 4000,
        color: "warning",
      });
    } else {
      addToast({
        title: "Error",
        description: error.message || "Error al actualizar el usuario",
        timeout: 3000,
        color: "danger",
      });
    }
  },
});

  const deleteMutation = useMutation({
    mutationFn: deleteUsuario,
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["usuarios"] });
      addToast({ title: "Éxito", description: "Usuario eliminado con éxito", timeout: 3000, color:"success" });
    },
    onError: () => {
      addToast({ title: "Error", description: "Error al eliminar el usuario", timeout: 3000, color:"danger" });
    },
  });

  return {
    ...usuariosQuery,
    roles: rolesQuery.data,
    isLoadingRoles: rolesQuery.isLoading,
    updateUsuario: updateMutation.mutateAsync,
    deleteUsuario: deleteMutation.mutate,
  };
};

export const useToggleStaff = () => {
  const queryClient = useQueryClient();

return useMutation({
  mutationFn: async ({ id, nuevoValor }: { id: number; nuevoValor: boolean }) => {
    const response = await api.patch(`${API_URL}user/${id}/`, {
      estado: nuevoValor,
    });
    return { id, nuevoValor };
  },
  onSuccess: () => {
    queryClient.invalidateQueries({ queryKey: ["usuarios"] });
  },
  onError: (error: any) => {
    const response = error?.response;

    if (response?.status === 403) {
      const mensaje = response?.data?.message || "No tienes permiso para modificar este usuario.";
      addToast({
        title: "Acción no permitida",
        description: mensaje,
        timeout: 4000,
        color: "warning",
      });
    } else {
      addToast({
        title: "Error",
        description: error.message || "Error al actualizar el estado del usuario",
        timeout: 3000,
        color: "danger",
      });
    }
  },
});
  
};
