import React, { useState } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { FaSchool, FaTable, FaThLarge, FaSearch, FaEdit, FaTrash, FaEye } from 'react-icons/fa';
import { useForm } from '@inertiajs/react';

export default function Index({ auth, schools }) {
    const [viewMode, setViewMode] = useState('table');
    const [searchQuery, setSearchQuery] = useState('');
    const [statusFilter, setStatusFilter] = useState('');
    const [typeFilter, setTypeFilter] = useState('');
    const [countryFilter, setCountryFilter] = useState('');

    const { delete: destroy } = useForm();

    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this school?')) {
            destroy(route('schools.destroy', id));
        }
    };

    const filteredSchools = schools.filter(school => {
        const matchesSearch = school.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                            school.address.toLowerCase().includes(searchQuery.toLowerCase());
        const matchesStatus = !statusFilter || school.status === statusFilter;
        const matchesType = !typeFilter || school.type === typeFilter;
        const matchesCountry = !countryFilter || school.country === countryFilter;

        return matchesSearch && matchesStatus && matchesType && matchesCountry;
    });

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Schools</h2>}
        >
            <Head title="Schools" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Card>
                        <CardHeader>
                            <div className="flex justify-between items-center">
                                <div className="flex items-center">
                                    <FaSchool className="h-6 w-6 text-indigo-600 mr-3" />
                                    <div>
                                        <h4 className="text-lg font-medium text-gray-900">Institutions</h4>
                                        <p className="text-sm text-gray-500">Manage your educational institutions</p>
                                    </div>
                                </div>
                                <div className="flex gap-2">
                                    <div className="btn-group">
                                        <button
                                            type="button"
                                            className={`btn ${viewMode === 'table' ? 'btn-primary' : 'btn-light'}`}
                                            onClick={() => setViewMode('table')}
                                            title="Table View"
                                        >
                                            <FaTable />
                                        </button>
                                        <button
                                            type="button"
                                            className={`btn ${viewMode === 'grid' ? 'btn-primary' : 'btn-light'}`}
                                            onClick={() => setViewMode('grid')}
                                            title="Grid View"
                                        >
                                            <FaThLarge />
                                        </button>
                                    </div>
                                    <Link href={route('schools.create')} className="btn btn-primary">
                                        <FaSchool className="mr-2" />
                                        Add Institution
                                    </Link>
                                </div>
                            </div>
                        </CardHeader>

                        <CardBody>
                            {/* Search and Filters */}
                            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <FaSearch className="h-5 w-5 text-gray-400" />
                                    </div>
                                    <input
                                        type="text"
                                        className="form-control pl-10"
                                        placeholder="Search schools..."
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                    />
                                </div>
                                <select
                                    className="form-select"
                                    value={statusFilter}
                                    onChange={(e) => setStatusFilter(e.target.value)}
                                >
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <select
                                    className="form-select"
                                    value={typeFilter}
                                    onChange={(e) => setTypeFilter(e.target.value)}
                                >
                                    <option value="">All Types</option>
                                    <option value="public">Public</option>
                                    <option value="private">Private</option>
                                    <option value="charter">Charter</option>
                                </select>
                                <select
                                    className="form-select"
                                    value={countryFilter}
                                    onChange={(e) => setCountryFilter(e.target.value)}
                                >
                                    <option value="">All Countries</option>
                                    {[...new Set(schools.map(school => school.country))].map(country => (
                                        <option key={country} value={country}>{country}</option>
                                    ))}
                                </select>
                            </div>

                            {/* Table View */}
                            {viewMode === 'table' && (
                                <div className="overflow-x-auto">
                                    <table className="table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Address</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th className="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {filteredSchools.map(school => (
                                                <tr key={school.id}>
                                                    <td>
                                                        <div className="flex items-center">
                                                            <div>
                                                                <h6 className="font-medium">{school.name}</h6>
                                                                <p className="text-sm text-gray-500">{school.type}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <p className="text-sm truncate max-w-xs">{school.address}</p>
                                                            <p className="text-sm text-gray-500">{school.city}, {school.country}</p>
                                                        </div>
                                                    </td>
                                                    <td>{school.phone}</td>
                                                    <td className="truncate max-w-xs">{school.email}</td>
                                                    <td>
                                                        <span className={`badge ${school.status === 'active' ? 'badge-success' : 'badge-danger'}`}>
                                                            {school.status}
                                                        </span>
                                                    </td>
                                                    <td className="text-right">
                                                        <div className="flex justify-end gap-2">
                                                            <Link
                                                                href={route('schools.show', school.id)}
                                                                className="btn btn-light btn-sm"
                                                                title="View Details"
                                                            >
                                                                <FaEye />
                                                            </Link>
                                                            <Link
                                                                href={route('schools.edit', school.id)}
                                                                className="btn btn-light btn-sm"
                                                                title="Edit"
                                                            >
                                                                <FaEdit />
                                                            </Link>
                                                            <button
                                                                onClick={() => handleDelete(school.id)}
                                                                className="btn btn-light btn-sm text-red-600"
                                                                title="Delete"
                                                            >
                                                                <FaTrash />
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            )}

                            {/* Grid View */}
                            {viewMode === 'grid' && (
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    {filteredSchools.map(school => (
                                        <Card key={school.id}>
                                            <CardBody>
                                                <div className="flex justify-between items-start mb-4">
                                                    <div>
                                                        <h5 className="font-medium text-lg mb-1">{school.name}</h5>
                                                        <span className={`badge ${school.status === 'active' ? 'badge-success' : 'badge-danger'}`}>
                                                            {school.status}
                                                        </span>
                                                    </div>
                                                    <div className="flex gap-2">
                                                        <Link
                                                            href={route('schools.show', school.id)}
                                                            className="btn btn-light btn-sm"
                                                            title="View Details"
                                                        >
                                                            <FaEye />
                                                        </Link>
                                                        <Link
                                                            href={route('schools.edit', school.id)}
                                                            className="btn btn-light btn-sm"
                                                            title="Edit"
                                                        >
                                                            <FaEdit />
                                                        </Link>
                                                        <button
                                                            onClick={() => handleDelete(school.id)}
                                                            className="btn btn-light btn-sm text-red-600"
                                                            title="Delete"
                                                        >
                                                            <FaTrash />
                                                        </button>
                                                    </div>
                                                </div>
                                                <div className="space-y-2">
                                                    <p className="text-sm">
                                                        <span className="text-gray-500">Type:</span> {school.type}
                                                    </p>
                                                    <p className="text-sm">
                                                        <span className="text-gray-500">Address:</span> {school.address}
                                                    </p>
                                                    <p className="text-sm">
                                                        <span className="text-gray-500">Phone:</span> {school.phone}
                                                    </p>
                                                    <p className="text-sm">
                                                        <span className="text-gray-500">Email:</span> {school.email}
                                                    </p>
                                                </div>
                                            </CardBody>
                                        </Card>
                                    ))}
                                </div>
                            )}

                            {filteredSchools.length === 0 && (
                                <div className="text-center py-12">
                                    <FaSchool className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                                    <h5 className="text-lg font-medium text-gray-900 mb-2">No schools found</h5>
                                    <p className="text-gray-500 mb-4">Get started by adding your first institution</p>
                                    <Link href={route('schools.create')} className="btn btn-primary">
                                        <FaSchool className="mr-2" />
                                        Add Institution
                                    </Link>
                                </div>
                            )}
                        </CardBody>
                    </Card>
                </div>
            </div>
        </AuthenticatedLayout>
    );
} 