import { useForm } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import TextArea from '@/Components/TextArea';

export default function Edit({ auth, school }) {
    const { data, setData, put, processing, errors } = useForm({
        name: school.name,
        code: school.code,
        description: school.description,
        address: school.address,
        city: school.city,
        state: school.state,
        country: school.country,
        postal_code: school.postal_code,
        phone: school.phone,
        email: school.email,
        website: school.website,
        principal_name: school.principal_name,
        type: school.type,
        capacity: school.capacity,
        status: school.status,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route('schools.update', school.id));
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Modifier l'école</h2>}
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <form onSubmit={submit} className="space-y-6">
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <InputLabel htmlFor="name" value="Nom de l'école" />
                                        <TextInput
                                            id="name"
                                            type="text"
                                            name="name"
                                            value={data.name}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('name', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.name} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="code" value="Code" />
                                        <TextInput
                                            id="code"
                                            type="text"
                                            name="code"
                                            value={data.code}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('code', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.code} className="mt-2" />
                                    </div>

                                    <div className="md:col-span-2">
                                        <InputLabel htmlFor="description" value="Description" />
                                        <TextArea
                                            id="description"
                                            name="description"
                                            value={data.description}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('description', e.target.value)}
                                        />
                                        <InputError message={errors.description} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="address" value="Adresse" />
                                        <TextInput
                                            id="address"
                                            type="text"
                                            name="address"
                                            value={data.address}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('address', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.address} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="city" value="Ville" />
                                        <TextInput
                                            id="city"
                                            type="text"
                                            name="city"
                                            value={data.city}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('city', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.city} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="state" value="État/Région" />
                                        <TextInput
                                            id="state"
                                            type="text"
                                            name="state"
                                            value={data.state}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('state', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.state} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="country" value="Pays" />
                                        <TextInput
                                            id="country"
                                            type="text"
                                            name="country"
                                            value={data.country}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('country', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.country} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="postal_code" value="Code postal" />
                                        <TextInput
                                            id="postal_code"
                                            type="text"
                                            name="postal_code"
                                            value={data.postal_code}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('postal_code', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.postal_code} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="phone" value="Téléphone" />
                                        <TextInput
                                            id="phone"
                                            type="tel"
                                            name="phone"
                                            value={data.phone}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('phone', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.phone} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="email" value="Email" />
                                        <TextInput
                                            id="email"
                                            type="email"
                                            name="email"
                                            value={data.email}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('email', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.email} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="website" value="Site web" />
                                        <TextInput
                                            id="website"
                                            type="url"
                                            name="website"
                                            value={data.website}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('website', e.target.value)}
                                        />
                                        <InputError message={errors.website} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="principal_name" value="Nom du directeur" />
                                        <TextInput
                                            id="principal_name"
                                            type="text"
                                            name="principal_name"
                                            value={data.principal_name}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('principal_name', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.principal_name} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="type" value="Type d'école" />
                                        <select
                                            id="type"
                                            name="type"
                                            value={data.type}
                                            className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            onChange={(e) => setData('type', e.target.value)}
                                            required
                                        >
                                            <option value="">Sélectionner un type</option>
                                            <option value="primary">Primaire</option>
                                            <option value="secondary">Secondaire</option>
                                            <option value="high">Lycée</option>
                                            <option value="university">Université</option>
                                        </select>
                                        <InputError message={errors.type} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="capacity" value="Capacité" />
                                        <TextInput
                                            id="capacity"
                                            type="number"
                                            name="capacity"
                                            value={data.capacity}
                                            className="mt-1 block w-full"
                                            onChange={(e) => setData('capacity', e.target.value)}
                                            required
                                        />
                                        <InputError message={errors.capacity} className="mt-2" />
                                    </div>

                                    <div>
                                        <InputLabel htmlFor="status" value="Statut" />
                                        <select
                                            id="status"
                                            name="status"
                                            value={data.status}
                                            className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                            onChange={(e) => setData('status', e.target.value)}
                                            required
                                        >
                                            <option value="active">Actif</option>
                                            <option value="inactive">Inactif</option>
                                            <option value="pending">En attente</option>
                                        </select>
                                        <InputError message={errors.status} className="mt-2" />
                                    </div>
                                </div>

                                <div className="flex items-center justify-end">
                                    <PrimaryButton className="ml-4" disabled={processing}>
                                        Mettre à jour l'école
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
} 