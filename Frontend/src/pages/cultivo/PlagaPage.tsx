import React, { useState, ChangeEvent, FormEvent } from "react";
import { useNavigate } from "react-router-dom";
import DefaultLayout from "@/layouts/default";
import { ReuInput } from "@/components/globales/ReuInput";
import { Plaga } from "@/types/cultivo/Plaga"; 
import { useRegistrarPlaga } from "@/hooks/cultivo/useplaga";
import { useTipoPlagas } from "@/hooks/cultivo/usetipoplaga";
import Formulario from "@/components/globales/Formulario";
import { Plus } from "lucide-react";
import { ModalTipoPlaga } from "@/components/cultivo/ModalTipoPlaga";
const PlagaPage: React.FC = () => {
  const [plaga, setPlaga] = useState<Plaga>({
    fk_tipo_plaga: 0,
    nombre: "",
    descripcion: "",
    img: null,
  });

  const mutation = useRegistrarPlaga();
  const navigate = useNavigate();
  const { data: tiposPlaga } = useTipoPlagas();
  const [openTipoPlaga, setOpenTipoPlaga] = useState(false);

  const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files.length > 0) {
      setPlaga((prev) => ({ ...prev, img: e.target.files![0] }));
    }
  };

  const handleTipoPlagaChange = (e: ChangeEvent<HTMLSelectElement>) => {
    const tipoPlagaId = parseInt(e.target.value, 10);
    setPlaga((prev) => ({ ...prev, fk_tipo_plaga: tipoPlagaId }));
  };

  const handleSubmit =(e: FormEvent<HTMLFormElement>)=>{
    e.preventDefault();
    mutation.mutate(plaga);
  };

  return (
    <DefaultLayout>
      <Formulario
      title="Registro de Plaga"
      onSubmit={handleSubmit}
      isSubmitting={mutation.isPending}
      buttonText="Guardar"
      >
        <ModalTipoPlaga
        isOpen={openTipoPlaga}
        onOpenChange={setOpenTipoPlaga}
        />
        <ReuInput
          label="Nombre"
          placeholder="Ingrese el nombre"
          type="text"
          value={plaga.nombre}
          onChange={(e) => setPlaga({ ...plaga, nombre: e.target.value })}
        />

        <ReuInput
          label="Descripción"
          placeholder="Ingrese la descripción"
          type="text"
          value={plaga.descripcion}
          onChange={(e) => setPlaga({ ...plaga, descripcion: e.target.value })}
        />

        <div className="mb-6">
        <label className="block text-sm font-medium text-gray-700">Imagen</label>
          <input
            type="file"
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            id="imagen"
            onChange={handleFileChange}
            accept="image/*"
          />
          
        </div>

        <div className="mb-4">
        <div className="flex items-center gap-2 mb-1">
                        <label className="block text-sm font-medium text-gray-700">Tipo plaga</label>
                        <button 
                            className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
                            onClick={() => setOpenTipoPlaga(true)}
                            type="button"
                        >
                            <Plus className="h-4 w-4" />
                        </button>
                    </div>          <select
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            value={plaga.fk_tipo_plaga || ""}
            onChange={handleTipoPlagaChange}
          >
            <option value="">Seleccione un tipo de plaga</option>
          
             {tiposPlaga?.map((tipo) => (
              <option key={tipo.id} value={tipo.id}>{tipo.nombre}</option>
            ))} */
          </select>
        </div>
        
        <div className="col-span-1 md:col-span-2 flex justify-center">
        <button
            className="w-full max-w-md px-4 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm uppercase tracking-wide"
            type="button"
          onClick={() => navigate("/cultivo/listarplaga")}
        >
          Listar Plagas
        </button>
        </div>
      </Formulario>
      
    </DefaultLayout>
  );
};

export default PlagaPage;