import { useState } from 'react';
import { Link } from '@inertiajs/react';
import { FaEye, FaEdit, FaTrash } from 'react-icons/fa';

export default function Index({ schools }) {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredSchools = schools.filter(school => 
        school.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        school.email.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div className="py-12">
            <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div className="p-6 text-gray-900">
                        <div className="flex justify-between items-center mb-6">
                            <h2 className="text-2xl font-bold">Écoles</h2>
                            <Link
                                href={route('schools.create')}
                                className="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Ajouter une école
                            </Link>
                        </div>

                        <div className="mb-6">
                            <input
                                type="text"
                                placeholder="Rechercher une école..."
                                className="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                value={searchTerm}
                                onChange={(e) => setSearchTerm(e.target.value)}
                            />
                        </div>

                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nom
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Adresse
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Téléphone
                                        </th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {filteredSchools.length > 0 ? (
                                        filteredSchools.map((school) => (
                                            <tr key={school.id}>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm font-medium text-gray-900">{school.name}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-500">{school.address}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-500">{school.phone}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap">
                                                    <div className="text-sm text-gray-500">{school.email}</div>
                                                </td>
                                                <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div className="flex justify-end space-x-2">
                                                        <Link
                                                            href={route('schools.show', school.id)}
                                                            className="text-blue-600 hover:text-blue-900"
                                                        >
                                                            <FaEye className="w-5 h-5" />
                                                        </Link>
                                                        <Link
                                                            href={route('schools.edit', school.id)}
                                                            className="text-indigo-600 hover:text-indigo-900"
                                                        >
                                                            <FaEdit className="w-5 h-5" />
                                                        </Link>
                                                        <Link
                                                            href={route('schools.destroy', school.id)}
                                                            method="delete"
                                                            as="button"
                                                            className="text-red-600 hover:text-red-900"
                                                        >
                                                            <FaTrash className="w-5 h-5" />
                                                        </Link>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-4 text-center text-sm text-gray-500">
                                                Aucune école trouvée
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
} 