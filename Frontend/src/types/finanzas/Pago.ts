export interface Pago {
  id?: number;
  usuario_id: number;
  usuario_nombre?: string;
  usuario_rol?: string;
  salario_id: number;
  fecha_inicio: string;
  fecha_fin: string;
  horas_trabajadas: number;
  jornales: number;
  total_pago: number;
  fecha_calculo?: string;
  actividades: number[];
}

export interface CalculoPagoParams {
  usuario_id: number;
  fecha_inicio: string;
  fecha_fin: string;
}

export interface PagoCreateParams {
  usuario_id: number;
  salario_id: number;
  fecha_inicio: string;
  fecha_fin: string;
  horas_trabajadas?: number;
  jornales?: number;
  total_pago?: number;
  actividades?: number[];
}