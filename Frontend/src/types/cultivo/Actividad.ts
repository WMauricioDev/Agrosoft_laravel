export interface ActividadForm {
  tipo_actividad_id: number;
  descripcion: string;
  fecha_inicio: string;
  fecha_fin: string;
  cultivo_id: number;
  estado: 'PENDIENTE' | 'EN_PROCESO' | 'COMPLETADA' | 'CANCELADA';
  prioridad: 'ALTA' | 'MEDIA' | 'BAJA';
  instrucciones_adicionales?: string;
  usuarios: number[];
  insumos?: { insumo_id: number; cantidad_usada: number }[];
  herramientas?: {
    herramienta_id: number;
    cantidad_entregada: number;
    entregada?: boolean;
    devuelta?: boolean;
    fecha_devolucion?: string | null;
  }[];
  prestamos_insumos?: { id: number; insumo_id: number; cantidad_usada: number; insumo_nombre?: string }[];
  prestamos_herramientas?: {
    id: number;
    herramienta_id: number;
    cantidad_entregada: number;
    herramienta_nombre?: string;
  }[];
  usuarios_data?: { id: number; nombre: string }[];
  tipo_actividad_nombre?: string;
  cultivo_nombre?: string;
}