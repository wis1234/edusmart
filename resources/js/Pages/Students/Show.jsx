import React from 'react';
import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import {
    FiUser,
    FiCalendar,
    FiBook,
    FiUsers,
    FiHeart,
    FiMapPin,
    FiPhone,
    FiMail,
    FiAward,
    FiActivity,
    FiClipboard,
    FiStar,
    FiTrendingUp,
    FiBookOpen
} from 'react-icons/fi';

export default function Show({ student }) {
    const getStatusColor = (status) => {
        switch (status) {
            case 'active':
                return 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
            case 'inactive':
                return 'bg-red-50 text-red-700 ring-red-600/20';
            case 'graduated':
                return 'bg-blue-50 text-blue-700 ring-blue-600/20';
            case 'transferred':
                return 'bg-amber-50 text-amber-700 ring-amber-600/20';
            default:
                return 'bg-gray-50 text-gray-700 ring-gray-600/20';
        }
    };

    const InfoCard = ({ icon: Icon, title, value, className = '' }) => (
        <div className={`bg-white rounded-xl shadow-sm p-6 ${className}`}>
            <div className="flex items-center space-x-3">
                <div className="flex-shrink-0">
                    <div className="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center">
                        <Icon className="h-5 w-5 text-blue-600" />
                    </div>
                </div>
                <div>
                    <p className="text-sm font-medium text-gray-500">{title}</p>
                    <p className="mt-1 text-lg font-semibold text-gray-900">{value || 'Not Available'}</p>
                </div>
            </div>
        </div>
    );

    const EvaluationCard = ({ title, score, description, icon: Icon }) => (
        <div className="bg-white rounded-xl shadow-sm p-6">
            <div className="flex items-center justify-between">
                <div className="flex items-center space-x-3">
                    <div className="h-10 w-10 rounded-lg bg-purple-50 flex items-center justify-center">
                        <Icon className="h-5 w-5 text-purple-600" />
                    </div>
                    <div>
                        <h3 className="text-lg font-semibold text-gray-900">{title}</h3>
                        <p className="text-sm text-gray-500">{description}</p>
                    </div>
                </div>
                <div className="text-2xl font-bold text-purple-600">{score}</div>
            </div>
            <div className="mt-4">
                <div className="h-2 bg-purple-100 rounded-full">
                    <div 
                        className="h-2 bg-purple-600 rounded-full" 
                        style={{ width: `${Math.min(100, (score / 5) * 100)}%` }}
                    />
                </div>
            </div>
        </div>
    );

    return (
        <MainLayout>
            <Head title={`${student.first_name} ${student.last_name}`} />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                        <div className="p-6">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center space-x-6">
                                    <div className="relative">
                                        <img
                                            src={student.profile_photo
                                                ? `/storage/${student.profile_photo}`
                                                : `https://ui-avatars.com/api/?name=${encodeURIComponent(student.first_name + ' ' + student.last_name)}&background=random`
                                            }
                                            alt={`${student.first_name} ${student.last_name}`}
                                            className="h-24 w-24 rounded-full object-cover ring-4 ring-white"
                                        />
                                        <span className={`absolute bottom-0 right-0 h-5 w-5 rounded-full ring-2 ring-white
                                            ${student.status === 'active' ? 'bg-emerald-400' :
                                              student.status === 'inactive' ? 'bg-red-400' :
                                              student.status === 'graduated' ? 'bg-blue-400' :
                                              'bg-amber-400'}`}
                                        />
                                    </div>
                                    <div>
                                        <h1 className="text-2xl font-bold text-gray-900">
                                            {student.first_name} {student.last_name}
                                        </h1>
                                        <div className="mt-1 flex items-center space-x-2">
                                            <FiBookOpen className="h-4 w-4 text-gray-400" />
                                            <span className="text-gray-500">{student.admission_number}</span>
                                            <span className={`inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset ${getStatusColor(student.status)}`}>
                                                {student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div className="flex space-x-3">
                                    <Link href={route('students.edit', student.id)}>
                                        <button className="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                            Edit Student
                                        </button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Quick Info Cards */}
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <InfoCard
                            icon={FiBook}
                            title="Class"
                            value={student.class_room?.name}
                        />
                        <InfoCard
                            icon={FiCalendar}
                            title="Academic Year"
                            value={student.academic_year}
                        />
                        <InfoCard
                            icon={FiUsers}
                            title="Parent/Guardian"
                            value={student.parent?.name}
                        />
                    </div>

                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        {/* Personal Information */}
                        <div className="lg:col-span-2">
                            <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Personal Information</h2>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <InfoCard
                                            icon={FiCalendar}
                                            title="Date of Birth"
                                            value={student.date_of_birth}
                                        />
                                        <InfoCard
                                            icon={FiHeart}
                                            title="Blood Group"
                                            value={student.blood_group}
                                        />
                                        <InfoCard
                                            icon={FiMapPin}
                                            title="Address"
                                            value={student.address}
                                        />
                                        <InfoCard
                                            icon={FiPhone}
                                            title="Contact Number"
                                            value={student.contact_number}
                                        />
                                    </div>
                                </div>
                            </div>

                            {/* Medical Information */}
                            <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Medical Information</h2>
                                    <div className="space-y-4">
                                        {student.medical_conditions?.split(', ').map((condition, index) => (
                                            <div key={index} className="flex items-center space-x-2">
                                                <div className="h-2 w-2 rounded-full bg-red-400" />
                                                <span className="text-gray-700">{condition}</span>
                                            </div>
                                        ))}
                                        {!student.medical_conditions && (
                                            <p className="text-gray-500">No medical conditions reported</p>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Academic Evaluation */}
                            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Academic Evaluation</h2>
                                    <div className="space-y-4">
                                        <EvaluationCard
                                            title="Academic Performance"
                                            score={4.5}
                                            description="Overall academic achievement"
                                            icon={FiAward}
                                        />
                                        <EvaluationCard
                                            title="Attendance"
                                            score={4.8}
                                            description="Class attendance rate"
                                            icon={FiClipboard}
                                        />
                                        <EvaluationCard
                                            title="Behavior"
                                            score={4.2}
                                            description="Conduct and discipline"
                                            icon={FiStar}
                                        />
                                        <EvaluationCard
                                            title="Progress"
                                            score={4.0}
                                            description="Learning progress and improvement"
                                            icon={FiTrendingUp}
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Sidebar */}
                        <div className="lg:col-span-1">
                            {/* Parent Information */}
                            <div className="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Parent/Guardian Information</h2>
                                    <div className="space-y-4">
                                        <div className="flex items-center space-x-3">
                                            <div className="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                                <FiUser className="h-5 w-5 text-gray-600" />
                                            </div>
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">{student.parent?.name || 'Not Available'}</p>
                                                <p className="text-sm text-gray-500">Parent/Guardian</p>
                                            </div>
                                        </div>
                                        {student.parent?.contact_number && (
                                            <div className="flex items-center space-x-2 text-sm text-gray-500">
                                                <FiPhone className="h-4 w-4" />
                                                <span>{student.parent.contact_number}</span>
                                            </div>
                                        )}
                                        {student.parent?.email && (
                                            <div className="flex items-center space-x-2 text-sm text-gray-500">
                                                <FiMail className="h-4 w-4" />
                                                <span>{student.parent.email}</span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>

                            {/* Achievements */}
                            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                                <div className="p-6">
                                    <h2 className="text-lg font-semibold text-gray-900 mb-4">Achievements</h2>
                                    <div className="space-y-4">
                                        <div className="flex items-center space-x-3">
                                            <div className="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                <FiAward className="h-4 w-4 text-blue-600" />
                                            </div>
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Academic Excellence</p>
                                                <p className="text-xs text-gray-500">First Term 2023</p>
                                            </div>
                                        </div>
                                        <div className="flex items-center space-x-3">
                                            <div className="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                                <FiActivity className="h-4 w-4 text-purple-600" />
                                            </div>
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Sports Achievement</p>
                                                <p className="text-xs text-gray-500">School Athletics 2023</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </MainLayout>
    );
}
