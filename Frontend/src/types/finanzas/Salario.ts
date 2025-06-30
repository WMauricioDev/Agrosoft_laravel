export interface Salario {
  id: number;
  rol_id: number;
  rol?: {
    id: number;
    nombre: string;
  };
  fecha_de_implementacion: string;
  valor_jornal: number;
  activo: boolean;
  valor_jornalFormatted?: string; 
  rol_nombre?: string;
}