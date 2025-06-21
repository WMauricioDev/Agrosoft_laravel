import React, { useState } from "react";
import ReuModal from "../globales/ReuModal";
import { ReuInput } from "../globales/ReuInput";
import { useRegistrarCosecha } from "@/hooks/cultivo/usecosecha";
import { useCultivos } from "@/hooks/cultivo/useCultivo";
import { useUnidadesMedida } from "@/hooks/inventario/useInsumo";
import { Cosecha } from "@/types/cultivo/Cosecha";
import { ModalCultivo } from "./ModalCultivo";
import { ModalUnidadMedida } from "./ModalUnidadMedida";
import { Plus } from "lucide-react";

interface ModalCosechaProps {
  isOpen: boolean;
  onOpenChange: (open: boolean) => void;
  onSuccess?: () => void;
}

export const ModalCosecha = ({ isOpen, onOpenChange, onSuccess }: ModalCosechaProps) => {
  const [nuevaCosecha, setNuevaCosecha] = useState<Cosecha>({
    id_cultivo: 0,
    cantidad: 0,
    unidades_de_medida: 0,
    fecha: "",
    cultivo_nombre: "",
  });

  const { data: cultivos } = useCultivos();
  const { data: unidadesMedida } = useUnidadesMedida();
  const mutation = useRegistrarCosecha();
  const [openCultivo, setOpenCultivo] = useState(false);
  const [openUnidadMedida, setOpenUnidadMedida] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setNuevaCosecha((prev) => ({
      ...prev,
      [name]: name === "unidades_de_medida" || name === "fecha" ? value : Number(value),
    }));
  };

  const handleSubmit = () => {
    mutation.mutate(nuevaCosecha, {
      onSuccess: () => {
        onOpenChange(false);
        setNuevaCosecha({
          id_cultivo: 0,
          cantidad: 0,
          unidades_de_medida: 0,
          fecha: "",
          cultivo_nombre: "",
        });
        onSuccess?.();
      },
    });
  };

  return (
    <ReuModal
      isOpen={isOpen}
      onOpenChange={onOpenChange}
      title="Registrar Nueva Cosecha"
      onConfirm={handleSubmit}
      confirmText="Guardar"
      cancelText="Cancelar"
    >
    <ModalCultivo
    isOpen={openCultivo}
    onOpenChange={setOpenCultivo}
    />
    <ModalUnidadMedida
    isOpen={openUnidadMedida}
    onOpenChange={setOpenUnidadMedida}
    />
      <div className="space-y-4">
        <div>
        <div className="flex items-center gap-2 mb-1">
          <label className="block text-sm font-medium text-gray-700">Cultivo</label>
              <button 
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenCultivo(true)}
                type="button"
                >
                <Plus className="h-4 w-4" />
              </button>
        </div>
          <select
            name="id_cultivo"
            value={nuevaCosecha.id_cultivo || ""}
            onChange={handleChange}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
          >
            <option value="0">Seleccione un cultivo</option>
            {cultivos?.map((cultivo) => (
              <option key={cultivo.id} value={cultivo.id}>{cultivo.nombre}</option>
            ))}
          </select>
        </div>

        <ReuInput
          label="Cantidad"
          placeholder="Ingrese la cantidad"
          type="number"
          value={nuevaCosecha.cantidad.toString()}
          onChange={(e) =>
            setNuevaCosecha({ ...nuevaCosecha, cantidad: Number(e.target.value) })
          }
        />

        <div>
        <div className="flex items-center gap-2 mb-1">
          <label className="block text-sm font-medium text-gray-700">Unidad de medida</label>
              <button 
                className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                onClick={() => setOpenUnidadMedida(true)}
                type="button"
                >
                <Plus className="h-4 w-4" />
              </button>
        </div>          <select
            name="unidades_de_medida"
            value={nuevaCosecha.unidades_de_medida || ""}
            onChange={handleChange}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 p-2 border"
          >
            <option value="0">Seleccione una unidad</option>
            {unidadesMedida?.map((unidad) => (
              <option key={unidad.id} value={unidad.id}>{unidad.nombre}</option>
            ))}
          </select>
        </div>

        <ReuInput
          label="Fecha de recolección"
          type="date"
          value={nuevaCosecha.fecha}
          onChange={(e) => setNuevaCosecha({ ...nuevaCosecha, fecha: e.target.value })}
        />
      </div>
    </ReuModal>
  );
};