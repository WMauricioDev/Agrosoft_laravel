import React, { useEffect } from "react";
import { useModalSensorForm } from "@/hooks/iot/sensores/useModalSensorForm";
import { addToast } from "@heroui/react";
import { Sensor, TipoSensor } from "@/types/iot/type";
import { Bancal } from "@/types/cultivo/Bancal";

interface ModalSensorProps {
  isOpen: boolean;
  onOpenChange: (open: boolean) => void;
  sensor: Sensor;
  tipoSensores: TipoSensor[] | undefined;
  bancales: Bancal[] | undefined;
  onConfirm: (sensor: Sensor | null) => void;
  isDelete?: boolean;
}

const ModalSensor: React.FC<ModalSensorProps> = ({
  isOpen,
  onOpenChange,
  sensor,
  tipoSensores,
  bancales,
  onConfirm,
  isDelete = false,
}) => {
  const { editedSensor, handleChange, handleConfirm, tipoSensoresError } = useModalSensorForm({
    sensor,
    tipoSensores,
    bancales: Array.isArray(bancales) ? bancales : [], // Asegurar que bancales sea un array
    onConfirm,
    isDelete,
  });

  useEffect(() => {
    console.log("[ModalSensor] Valor de bancales recibido: ", {
      bancales,
      isArray: Array.isArray(bancales),
      type: typeof bancales,
    });
    if (bancales && !Array.isArray(bancales)) {
      console.error("[ModalSensor] bancales no es un array:", bancales);
      addToast({
        title: "Error",
        description: "Los datos de bancales no son válidos. Contacta al equipo de cultivos.",
        timeout: 3000,
        color: "danger",
      });
    }
  }, [bancales]);

  if (!isOpen) return null;

  if (tipoSensoresError) {
    addToast({
      title: "Error",
      description: tipoSensoresError.message,
      timeout: 3000,
      color: "danger",
    });
    return <div>Error: {tipoSensoresError.message}</div>;
  }

  return (
    <div className="modal">
      <h2>{isDelete ? "Eliminar Sensor" : "Editar Sensor"}</h2>
      <form>
        <label>
          Nombre:
          <input
            type="text"
            value={editedSensor?.nombre || ""}
            onChange={(e) => handleChange("nombre", e)}
            disabled={isDelete}
          />
        </label>
        <label>
          Tipo de Sensor:
          <select
            value={editedSensor?.tipo_sensor || ""}
            onChange={(e) => handleChange("tipo_sensor", e)}
            disabled={isDelete}
          >
            <option value="">Seleccione un tipo</option>
            {tipoSensores?.map((tipo) => (
              <option key={tipo.tipo_sensor_id} value={tipo.nombre}>
                {tipo.nombre}
              </option>
            ))}
          </select>
        </label>
        <label>
          Bancal:
          <select
            value={editedSensor?.bancal_id || ""}
            onChange={(e) => handleChange("bancal_id", e)}
            disabled={isDelete}
          >
            <option value="">Sin bancal</option>
            {Array.isArray(bancales) && bancales.length > 0 ? (
              bancales.map((bancal) => (
                <option key={bancal.id} value={bancal.id}>
                  Bancal {bancal.id} {bancal.posY ? `(PosY: ${bancal.posY})` : ""}
                </option>
              ))
            ) : (
              <option value="" disabled>
                No hay bancales disponibles
              </option>
            )}
          </select>
        </label>
        <label>
          Medida Mínima:
          <input
            type="number"
            value={editedSensor?.medida_minima || ""}
            onChange={(e) => handleChange("medida_minima", e)}
            disabled={isDelete}
          />
        </label>
        <label>
          Medida Máxima:
          <input
            type="number"
            value={editedSensor?.medida_maxima || ""}
            onChange={(e) => handleChange("medida_maxima", e)}
            disabled={isDelete}
          />
        </label>
        <label>
          Descripción:
          <textarea
            value={editedSensor?.descripcion || ""}
            onChange={(e) => handleChange("descripcion", e)}
            disabled={isDelete}
          />
        </label>
        <button type="button" onClick={handleConfirm}>
          {isDelete ? "Eliminar" : "Guardar"}
        </button>
        <button type="button" onClick={() => onOpenChange(false)}>
          Cancelar
        </button>
      </form>
    </div>
  );
};

export default ModalSensor;