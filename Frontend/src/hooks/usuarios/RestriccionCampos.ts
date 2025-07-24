export function limpiarSoloLetrasYEspacios(valor: string): string {

  return valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
}
