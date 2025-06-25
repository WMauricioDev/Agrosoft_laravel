import React, { useState, FormEvent } from "react";
import DefaultLayout from "@/layouts/default";
import { ReuInput } from "@/components/globales/ReuInput";
import { useRegistrarEspecie } from "@/hooks/cultivo/useEspecie";
import { useTipoEspecies } from "@/hooks/cultivo/usetipoespecie";
import { useNavigate } from "react-router-dom";
import Formulario from "@/components/globales/Formulario";
import { ModalTipoEspecie } from "@/components/cultivo/ModalTipoEspecie";
import { Plus } from 'lucide-react';

const EspeciePage: React.FC = () => {
  const [especie, setEspecie] = useState({
    nombre: "",
    descripcion: "",
    largo_crecimiento: 0,
    tipo_especie_id: 0,
    img: "",
  });

  const mutation = useRegistrarEspecie();
  const { data: tiposEspecie } = useTipoEspecies();
  const navigate = useNavigate();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setEspecie((prev) => ({
      ...prev,
      [name]: name === "nombre" || name === "descripcion" || name === "img" ? value : Number(value),
    }));
  };
  const [openTipoEspecie, setOpenTipoEspecie] = useState(false);

  const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append("nombre", especie.nombre);
    formData.append("descripcion", especie.descripcion);
    formData.append("largo_crecimiento", especie.largo_crecimiento.toString());
    formData.append("tipo_especie_id", especie.tipo_especie_id.toString());
    formData.append("img", especie.img);

    mutation.mutate(formData);
  };

  return (
    <DefaultLayout>
      <Formulario
        title="Registro de Especie"
        onSubmit={handleSubmit}
        isSubmitting={mutation.isPending}
        buttonText="Guardar"
      >
        <ModalTipoEspecie
        isOpen={openTipoEspecie}
        onOpenChange={setOpenTipoEspecie}
        />
        <ReuInput
          label="Nombre"
          placeholder="Ingrese el nombre"
          type="text"
          value={especie.nombre}
          onChange={(e) => setEspecie({ ...especie, nombre: e.target.value })}
        />

        <ReuInput
          label="Descripción"
          placeholder="Ingrese la descripción"
          type="text"
          value={especie.descripcion}
          onChange={(e) => setEspecie({ ...especie, descripcion: e.target.value })}
        />

        <ReuInput
          label="Largo de Crecimiento"
          placeholder="Ingrese el tiempo en días"
          type="number"
          value={especie.largo_crecimiento}
          onChange={(e) => setEspecie({ ...especie, largo_crecimiento: Number(e.target.value) })}
        />

        <div className="mb-1">
          <div className="flex items-center gap-2 mb-1">
            <label className="block text-sm font-medium text-gray-700">Tipo de especie</label>
            <button 
              className="p-1 h-6 w-6 flex items-center justify-center rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500"
              onClick={() => setOpenTipoEspecie(true)}
              type="button"
            >
              <Plus className="h-4 w-4" />
            </button>
          </div>

          <select
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            name="tipo_especie_id"
            value={especie.tipo_especie_id}
            onChange={handleChange}
          >
            <option value="">Seleccione un tipo</option>
            {tiposEspecie?.map((tipo) => (
              <option key={tipo.id} value={tipo.id}>{tipo.nombre}</option>
            ))}
          </select>
        </div>

        <div className="col-span-1 sm:col-span-2">
          <label className="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
          <input
            type="file"
            className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            name="img"
            onChange={(e) => {
              if (e.target.files && e.target.files[0]) {
                setEspecie({ ...especie, img: e.target.files[0] as any });
              }
            }}
            accept="image/*"
          />
        </div>

        <div className="col-span-1 md:col-span-2 flex justify-center">
          <button
            className="w-full max-w-md px-4 py-3 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-all duration-200 shadow-md hover:shadow-lg font-medium text-sm uppercase tracking-wide"
            type="button"
            onClick={() => navigate("/cultivo/listarespecies/")}
          >
            Listar especies
          </button>
        </div>
      </Formulario>
    </DefaultLayout>
  );
};

export default EspeciePage;