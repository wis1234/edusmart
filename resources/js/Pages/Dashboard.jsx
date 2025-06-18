import React from 'react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { FaSchool, FaChalkboardTeacher, FaUserGraduate, FaUsers } from 'react-icons/fa';

export default function Dashboard({ auth, stats }) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Stats Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                        <Card>
                            <CardBody>
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                                        <FaSchool className="h-6 w-6 text-indigo-600" />
                                    </div>
                                    <div className="ml-4">
                                        <h6 className="text-sm font-medium text-gray-600">Total Schools</h6>
                                        <h3 className="text-2xl font-bold text-gray-900">{stats.schools_count}</h3>
                                        <p className="text-sm text-green-600">
                                            <span className="flex items-center">
                                                <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                                12% this month
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-green-100 rounded-lg p-3">
                                        <FaChalkboardTeacher className="h-6 w-6 text-green-600" />
                                    </div>
                                    <div className="ml-4">
                                        <h6 className="text-sm font-medium text-gray-600">Total Teachers</h6>
                                        <h3 className="text-2xl font-bold text-gray-900">{stats.teachers_count}</h3>
                                        <p className="text-sm text-green-600">
                                            <span className="flex items-center">
                                                <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                                8% this month
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                        <FaUserGraduate className="h-6 w-6 text-blue-600" />
                                    </div>
                                    <div className="ml-4">
                                        <h6 className="text-sm font-medium text-gray-600">Total Students</h6>
                                        <h3 className="text-2xl font-bold text-gray-900">{stats.students_count}</h3>
                                        <p className="text-sm text-green-600">
                                            <span className="flex items-center">
                                                <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                                15% this month
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </CardBody>
                        </Card>

                        <Card>
                            <CardBody>
                                <div className="flex items-center">
                                    <div className="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                                        <FaUsers className="h-6 w-6 text-purple-600" />
                                    </div>
                                    <div className="ml-4">
                                        <h6 className="text-sm font-medium text-gray-600">Total Parents</h6>
                                        <h3 className="text-2xl font-bold text-gray-900">{stats.parents_count}</h3>
                                        <p className="text-sm text-green-600">
                                            <span className="flex items-center">
                                                <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clipRule="evenodd" />
                                                </svg>
                                                10% this month
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </CardBody>
                        </Card>
                    </div>

                    {/* Quick Actions */}
                    <Card>
                        <CardHeader>
                            <h5 className="text-lg font-medium text-gray-900">Quick Actions</h5>
                        </CardHeader>
                        <CardBody>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                                <a href={route('schools.create')} className="block">
                                    <div className="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                                        <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 mb-4">
                                            <FaSchool className="h-6 w-6 text-indigo-600" />
                                        </div>
                                        <h6 className="text-gray-900 font-medium">Add School</h6>
                                    </div>
                                </a>

                                <a href={route('teachers.create')} className="block">
                                    <div className="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                                        <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-4">
                                            <FaChalkboardTeacher className="h-6 w-6 text-green-600" />
                                        </div>
                                        <h6 className="text-gray-900 font-medium">Add Teacher</h6>
                                    </div>
                                </a>

                                <a href={route('students.create')} className="block">
                                    <div className="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                                        <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-4">
                                            <FaUserGraduate className="h-6 w-6 text-blue-600" />
                                        </div>
                                        <h6 className="text-gray-900 font-medium">Add Student</h6>
                                    </div>
                                </a>

                                <a href={route('parents.create')} className="block">
                                    <div className="bg-white rounded-lg shadow-sm p-6 text-center hover:shadow-md transition-shadow">
                                        <div className="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 mb-4">
                                            <FaUsers className="h-6 w-6 text-purple-600" />
                                        </div>
                                        <h6 className="text-gray-900 font-medium">Add Parent</h6>
                                    </div>
                                </a>
                            </div>
                        </CardBody>
                    </Card>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
