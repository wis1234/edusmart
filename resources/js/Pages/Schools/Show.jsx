import { Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { FaEdit, FaTrash, FaUser, FaGraduationCap, FaChalkboardTeacher } from 'react-icons/fa';

export default function Show({ auth, school }) {
    const getStatusColor = (status) => {
        switch (status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-red-100 text-red-800';
            case 'pending':
                return 'bg-yellow-100 text-yellow-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    };

    const getTypeLabel = (type) => {
        switch (type) {
            case 'primary':
                return 'Primaire';
            case 'secondary':
                return 'Secondaire';
            case 'high':
                return 'Lycée';
            case 'university':
                return 'Université';
            default:
                return type;
        }
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Détails de l'école</h2>}
        >
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <div className="flex justify-between items-center mb-6">
                                <div>
                                    <h2 className="text-2xl font-bold">{school.name}</h2>
                                    <p className="text-gray-600">{school.code}</p>
                                </div>
                                <div className="space-x-2">
                                    <Link
                                        href={route('schools.edit', school.id)}
                                        className="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        <FaEdit className="mr-2" />
                                        Modifier
                                    </Link>
                                    <Link
                                        href={route('schools.destroy', school.id)}
                                        method="delete"
                                        as="button"
                                        className="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        <FaTrash className="mr-2" />
                                        Supprimer
                                    </Link>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div>
                                    <h4 className="text-lg font-semibold mb-2">Informations générales</h4>
                                    <div className="space-y-2">
                                        <p><span className="font-medium">Description :</span> {school.description}</p>
                                        <p><span className="font-medium">Type :</span> {getTypeLabel(school.type)}</p>
                                        <p><span className="font-medium">Capacité :</span> {school.capacity} étudiants</p>
                                        <p>
                                            <span className="font-medium">Statut :</span>
                                            <span className={`ml-2 px-2 py-1 rounded-full text-xs ${getStatusColor(school.status)}`}>
                                                {school.status === 'active' ? 'Actif' : school.status === 'inactive' ? 'Inactif' : 'En attente'}
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <h4 className="text-lg font-semibold mb-2">Contact</h4>
                                    <div className="space-y-2">
                                        <p><span className="font-medium">Directeur :</span> {school.principal_name}</p>
                                        <p><span className="font-medium">Téléphone :</span> {school.phone}</p>
                                        <p><span className="font-medium">Email :</span> {school.email}</p>
                                        <p><span className="font-medium">Site web :</span> {school.website}</p>
                                    </div>
                                </div>

                                <div>
                                    <h4 className="text-lg font-semibold mb-2">Adresse</h4>
                                    <div className="space-y-2">
                                        <p>{school.address}</p>
                                        <p>{school.city}, {school.state}</p>
                                        <p>{school.postal_code}</p>
                                        <p>{school.country}</p>
                                    </div>
                                </div>

                                <div>
                                    <h4 className="text-lg font-semibold mb-2">Informations système</h4>
                                    <div className="space-y-2">
                                        <p><span className="font-medium">Créé le :</span> {new Date(school.created_at).toLocaleDateString()}</p>
                                        <p><span className="font-medium">Créé par :</span> {school.created_by?.name}</p>
                                        <p><span className="font-medium">Dernière modification :</span> {new Date(school.updated_at).toLocaleDateString()}</p>
                                        <p><span className="font-medium">Modifié par :</span> {school.updated_by?.name}</p>
                                    </div>
                                </div>
                            </div>

                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div className="bg-gray-50 p-4 rounded-lg">
                                    <div className="flex items-center mb-4">
                                        <FaChalkboardTeacher className="text-blue-600 text-xl mr-2" />
                                        <h4 className="text-lg font-semibold">Enseignants</h4>
                                    </div>
                                    {school.teachers.length > 0 ? (
                                        <ul className="space-y-2">
                                            {school.teachers.map(teacher => (
                                                <li key={teacher.id} className="flex items-center">
                                                    <span className="w-2 h-2 bg-blue-600 rounded-full mr-2"></span>
                                                    {teacher.name}
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <p className="text-gray-500">Aucun enseignant</p>
                                    )}
                                </div>

                                <div className="bg-gray-50 p-4 rounded-lg">
                                    <div className="flex items-center mb-4">
                                        <FaUser className="text-green-600 text-xl mr-2" />
                                        <h4 className="text-lg font-semibold">Étudiants</h4>
                                    </div>
                                    {school.students.length > 0 ? (
                                        <ul className="space-y-2">
                                            {school.students.map(student => (
                                                <li key={student.id} className="flex items-center">
                                                    <span className="w-2 h-2 bg-green-600 rounded-full mr-2"></span>
                                                    {student.name}
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <p className="text-gray-500">Aucun étudiant</p>
                                    )}
                                </div>

                                <div className="bg-gray-50 p-4 rounded-lg">
                                    <div className="flex items-center mb-4">
                                        <FaGraduationCap className="text-purple-600 text-xl mr-2" />
                                        <h4 className="text-lg font-semibold">Classes</h4>
                                    </div>
                                    {school.classRooms.length > 0 ? (
                                        <ul className="space-y-2">
                                            {school.classRooms.map(classRoom => (
                                                <li key={classRoom.id} className="flex items-center">
                                                    <span className="w-2 h-2 bg-purple-600 rounded-full mr-2"></span>
                                                    {classRoom.name}
                                                </li>
                                            ))}
                                        </ul>
                                    ) : (
                                        <p className="text-gray-500">Aucune classe</p>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
} 