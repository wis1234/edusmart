import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import Table from '@/Components/UI/Table';
import Button from '@/Components/UI/Button';
import Input from '@/Components/UI/Input';
import Pagination from '@/Components/UI/Pagination';
import ConfirmDialog from '@/Components/UI/ConfirmDialog';
import { 
    FiSearch, 
    FiGrid, 
    FiList, 
    FiBookOpen, 
    FiAward,
    FiCalendar,
    FiPhone,
    FiMail,
    FiMapPin,
    FiActivity,
    FiUser,
    FiUsers,
    FiHeart,
    FiBook,
    FiPlus,
    FiEdit2,
    FiTrash2,
    FiCheck,
    FiX
} from 'react-icons/fi';

export default function Index({ students, filters, class_rooms, academic_years, can }) {
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [selectedStatus, setSelectedStatus] = useState(filters.status || '');
    const [selectedClassRoom, setSelectedClassRoom] = useState(filters.class_room_id || '');
    const [selectedAcademicYear, setSelectedAcademicYear] = useState(filters.academic_year || '');
    const [viewMode, setViewMode] = useState('grid');
    const [deleteDialog, setDeleteDialog] = useState({ isOpen: false, student: null });
    const [editingStudent, setEditingStudent] = useState(null);
    const [editForm, setEditForm] = useState({});

    // Remove unused state and calculations since Laravel handles pagination
    const currentPage = students.current_page;
    const totalPages = Math.ceil(students.total / students.per_page);

    // No need for filtering since Laravel handles it
    const studentsList = students.data || [];

    const updateFilters = (newFilters) => {
        router.get(route('students.index'), {
            ...newFilters,
            page: 1, // Reset to first page when filters change
        }, { 
            preserveState: true,
            preserveScroll: true,
        });
    };

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

    const StudentCard = ({ student }) => (
        <Link 
            href={route('students.show', student.id)} 
            className="block transform transition-all duration-200 hover:scale-105"
        >
            <div className="bg-white rounded-xl shadow-sm hover:shadow-md p-6 border border-gray-200">
                <div className="flex flex-col space-y-4">
                    <div className="flex items-center space-x-4">
                        <div className="relative">
                            <img
                                src={student.profile_photo
                                    ? `/storage/${student.profile_photo}`
                                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(student.first_name + ' ' + student.last_name)}&background=random`
                                }
                                alt={`${student.first_name} ${student.last_name}`}
                                className="h-20 w-20 rounded-full object-cover ring-4 ring-white"
                            />
                            <span className={`absolute bottom-0 right-0 h-4 w-4 rounded-full ring-2 ring-white
                                ${student.status === 'active' ? 'bg-emerald-400' :
                                  student.status === 'inactive' ? 'bg-red-400' :
                                  student.status === 'graduated' ? 'bg-blue-400' :
                                  'bg-amber-400'}`}
                            />
                        </div>
                        <div className="flex-1">
                            <h3 className="text-lg font-semibold text-gray-900">
                                {student.first_name} {student.last_name}
                            </h3>
                            <div className="mt-1 flex items-center space-x-2 text-sm text-gray-500">
                                <FiBookOpen className="h-4 w-4" />
                                <span>{student.admission_number}</span>
                            </div>
                            <div className="mt-1">
                                <span className={`inline-flex items-center rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset ${getStatusColor(student.status)}`}>
                                    {student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                        <div className="flex items-center space-x-2 text-sm text-gray-600">
                            <FiBook className="h-4 w-4 text-gray-400" />
                            <span>{student.class_room?.name || 'No Class'}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600">
                            <FiCalendar className="h-4 w-4 text-gray-400" />
                            <span>{student.academic_year}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600">
                            <FiUsers className="h-4 w-4 text-gray-400" />
                            <span>{student.parent?.name || 'No Parent'}</span>
                        </div>
                        <div className="flex items-center space-x-2 text-sm text-gray-600">
                            <FiHeart className="h-4 w-4 text-gray-400" />
                            <span>{student.blood_group || 'Not Set'}</span>
                        </div>
                    </div>

                    <div className="flex justify-between items-center pt-4 border-t border-gray-100">
                        <div className="flex items-center space-x-2">
                            <FiActivity className="h-4 w-4 text-emerald-500" />
                            <span className="text-sm font-medium text-emerald-600">View Details</span>
                        </div>
                        <div className="flex -space-x-2">
                            {/* Example achievement badges */}
                            <div className="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center" title="Academic Excellence">
                                <FiAward className="h-4 w-4 text-blue-600" />
                            </div>
                            <div className="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center" title="Sports Achievement">
                                <FiActivity className="h-4 w-4 text-purple-600" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Link>
    );

    const handleDelete = (student) => {
        setDeleteDialog({
            isOpen: true,
            student: student
        });
    };

    const confirmDelete = () => {
        if (deleteDialog.student) {
            router.delete(route('students.destroy', deleteDialog.student.id), {
                preserveScroll: true,
                preserveState: true,
                onSuccess: () => {
                    setDeleteDialog({ isOpen: false, student: null });
                },
            });
        }
    };

    const handleEdit = (student) => {
        setEditingStudent(student);
        setEditForm({
            first_name: student.first_name,
            last_name: student.last_name,
            admission_number: student.admission_number,
            class_room_id: student.class_room?.id || '',
            academic_year: student.academic_year,
            status: student.status,
        });
    };

    const handleSave = () => {
        router.put(route('students.update', editingStudent.id), editForm, {
            preserveScroll: true,
            onSuccess: () => {
                setEditingStudent(null);
                setEditForm({});
            },
        });
    };

    const handleCancel = () => {
        setEditingStudent(null);
        setEditForm({});
    };

    return (
        <MainLayout>
            <Head title="Students" />

            <div className="py-6">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    {/* Header */}
                    <div className="sm:flex sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900">Students</h2>
                            <p className="mt-1 text-sm text-gray-500">
                                Manage and view all students
                            </p>
                        </div>
                        {can.create_student && (
                            <div className="mt-4 sm:mt-0">
                                <Link href={route('students.create')}>
                                    <Button>
                                        <FiPlus className="h-4 w-4 mr-2" />
                                        Add Student
                                    </Button>
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Filters */}
                    <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <Input
                                    type="text"
                                    placeholder="Search students..."
                                    value={searchTerm}
                                    onChange={e => {
                                        setSearchTerm(e.target.value);
                                        updateFilters({
                                            search: e.target.value,
                                            status: selectedStatus,
                                            class_room_id: selectedClassRoom,
                                            academic_year: selectedAcademicYear,
                                        });
                                    }}
                                    leftIcon={<FiSearch className="h-5 w-5 text-gray-400" />}
                                />
                            </div>
                            <div>
                                <select
                                    value={selectedStatus}
                                    onChange={e => {
                                        setSelectedStatus(e.target.value);
                                        updateFilters({
                                            search: searchTerm,
                                            status: e.target.value,
                                            class_room_id: selectedClassRoom,
                                            academic_year: selectedAcademicYear,
                                        });
                                    }}
                                    className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                >
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="graduated">Graduated</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                            </div>
                            <div>
                                <select
                                    value={selectedClassRoom}
                                    onChange={e => {
                                        setSelectedClassRoom(e.target.value);
                                        updateFilters({
                                            search: searchTerm,
                                            status: selectedStatus,
                                            class_room_id: e.target.value,
                                            academic_year: selectedAcademicYear,
                                        });
                                    }}
                                    className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                >
                                    <option value="">All Classes</option>
                                    {class_rooms.map(classRoom => (
                                        <option key={classRoom.id} value={classRoom.id}>
                                            {classRoom.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <select
                                    value={selectedAcademicYear}
                                    onChange={e => {
                                        setSelectedAcademicYear(e.target.value);
                                        updateFilters({
                                            search: searchTerm,
                                            status: selectedStatus,
                                            class_room_id: selectedClassRoom,
                                            academic_year: e.target.value,
                                        });
                                    }}
                                    className="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                >
                                    <option value="">All Academic Years</option>
                                    {academic_years.map(year => (
                                        <option key={year} value={year}>
                                            {year}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                    </div>

                    {/* View Mode Toggle */}
                    <div className="flex justify-end mb-4">
                        <div className="inline-flex rounded-md shadow-sm">
                            <button
                                type="button"
                                onClick={() => setViewMode('grid')}
                                className={`relative inline-flex items-center px-3 py-2 rounded-l-md border text-sm font-medium ${
                                    viewMode === 'grid'
                                        ? 'bg-blue-50 text-blue-600 border-blue-500'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                }`}
                            >
                                <FiGrid className="h-4 w-4" />
                            </button>
                            <button
                                type="button"
                                onClick={() => setViewMode('list')}
                                className={`relative -ml-px inline-flex items-center px-3 py-2 rounded-r-md border text-sm font-medium ${
                                    viewMode === 'list'
                                        ? 'bg-blue-50 text-blue-600 border-blue-500'
                                        : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'
                                }`}
                            >
                                <FiList className="h-4 w-4" />
                            </button>
                        </div>
                    </div>

                    {/* Students Grid/List */}
                    {viewMode === 'grid' ? (
                        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            {studentsList.map(student => (
                                <div key={student.id} className="relative">
                                    <StudentCard student={student} />
                                    {can.update_student && (
                                        <div className="absolute top-4 right-4 flex gap-2">
                                            <Link
                                                href={route('students.edit', student.id)}
                                                className="p-2 bg-white rounded-full shadow hover:bg-gray-50"
                                            >
                                                <FiEdit2 className="h-4 w-4 text-gray-600" />
                                            </Link>
                                            {can.delete_student && (
                                                <button
                                                    onClick={() => handleDelete(student)}
                                                    className="p-2 bg-white rounded-full shadow hover:bg-gray-50"
                                                >
                                                    <FiTrash2 className="h-4 w-4 text-red-600" />
                                                </button>
                                            )}
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <div className="bg-white rounded-lg shadow-sm overflow-hidden">
                            <Table>
                                <Table.Head>
                                    <tr>
                                        <Table.Cell header>Name</Table.Cell>
                                        <Table.Cell header>Admission Number</Table.Cell>
                                        <Table.Cell header>Class</Table.Cell>
                                        <Table.Cell header>Academic Year</Table.Cell>
                                        <Table.Cell header>Status</Table.Cell>
                                        <Table.Cell header actions>Actions</Table.Cell>
                                    </tr>
                                </Table.Head>
                                <Table.Body>
                                    {studentsList.map(student => (
                                        <Table.Row 
                                            key={student.id}
                                            isEditing={editingStudent?.id === student.id}
                                        >
                                            <Table.Cell
                                                image={student.profile_photo ? `/storage/${student.profile_photo}` : null}
                                                imageAlt={`${student.first_name} ${student.last_name}`}
                                                imageSize="small"
                                            >
                                                {editingStudent?.id === student.id ? (
                                                    <div className="space-y-1">
                                                        <Input
                                                            type="text"
                                                            value={editForm.first_name}
                                                            onChange={e => setEditForm({ ...editForm, first_name: e.target.value })}
                                                            placeholder="First Name"
                                                        />
                                                        <Input
                                                            type="text"
                                                            value={editForm.last_name}
                                                            onChange={e => setEditForm({ ...editForm, last_name: e.target.value })}
                                                            placeholder="Last Name"
                                                        />
                                                    </div>
                                                ) : (
                                                    <div>
                                                        <div className="font-medium text-gray-900">
                                                            {student.first_name} {student.last_name}
                                                        </div>
                                                    </div>
                                                )}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {editingStudent?.id === student.id ? (
                                                    <Input
                                                        type="text"
                                                        value={editForm.admission_number}
                                                        onChange={e => setEditForm({ ...editForm, admission_number: e.target.value })}
                                                    />
                                                ) : (
                                                    student.admission_number
                                                )}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {editingStudent?.id === student.id ? (
                                                    <select
                                                        value={editForm.class_room_id}
                                                        onChange={e => setEditForm({ ...editForm, class_room_id: e.target.value })}
                                                        className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    >
                                                        <option value="">Select Class</option>
                                                        {class_rooms.map(classRoom => (
                                                            <option key={classRoom.id} value={classRoom.id}>
                                                                {classRoom.name}
                                                            </option>
                                                        ))}
                                                    </select>
                                                ) : (
                                                    student.class_room?.name || 'Not Assigned'
                                                )}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {editingStudent?.id === student.id ? (
                                                    <select
                                                        value={editForm.academic_year}
                                                        onChange={e => setEditForm({ ...editForm, academic_year: e.target.value })}
                                                        className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    >
                                                        <option value="">Select Year</option>
                                                        {academic_years.map(year => (
                                                            <option key={year} value={year}>
                                                                {year}
                                                            </option>
                                                        ))}
                                                    </select>
                                                ) : (
                                                    student.academic_year
                                                )}
                                            </Table.Cell>
                                            <Table.Cell>
                                                {editingStudent?.id === student.id ? (
                                                    <select
                                                        value={editForm.status}
                                                        onChange={e => setEditForm({ ...editForm, status: e.target.value })}
                                                        className="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                                    >
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                        <option value="graduated">Graduated</option>
                                                        <option value="transferred">Transferred</option>
                                                    </select>
                                                ) : (
                                                    <span className={`inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset ${getStatusColor(student.status)}`}>
                                                        {student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                                                    </span>
                                                )}
                                            </Table.Cell>
                                            <Table.Cell actions>
                                                <div className="flex items-center gap-2">
                                                    {editingStudent?.id === student.id ? (
                                                        <>
                                                            <button
                                                                onClick={handleSave}
                                                                className="text-green-600 hover:text-green-900"
                                                            >
                                                                <FiCheck className="h-4 w-4" />
                                                            </button>
                                                            <button
                                                                onClick={handleCancel}
                                                                className="text-gray-600 hover:text-gray-900"
                                                            >
                                                                <FiX className="h-4 w-4" />
                                                            </button>
                                                        </>
                                                    ) : (
                                                        <>
                                                            <Link
                                                                href={route('students.show', student.id)}
                                                                className="text-blue-600 hover:text-blue-900"
                                                            >
                                                                View
                                                            </Link>
                                                            {can.update_student && (
                                                                <button
                                                                    onClick={() => handleEdit(student)}
                                                                    className="text-gray-600 hover:text-gray-900"
                                                                >
                                                                    <FiEdit2 className="h-4 w-4" />
                                                                </button>
                                                            )}
                                                            {can.delete_student && (
                                                                <button
                                                                    onClick={() => handleDelete(student)}
                                                                    className="text-red-600 hover:text-red-900"
                                                                >
                                                                    <FiTrash2 className="h-4 w-4" />
                                                                </button>
                                                            )}
                                                        </>
                                                    )}
                                                </div>
                                            </Table.Cell>
                                        </Table.Row>
                                    ))}
                                </Table.Body>
                            </Table>
                        </div>
                    )}

                    {/* Pagination */}
                    {students.links && students.links.length > 3 && (
                        <div className="mt-6">
                            <Pagination links={students.links} />
                        </div>
                    )}

                    {/* Delete Confirmation Dialog */}
                    <ConfirmDialog
                        isOpen={deleteDialog.isOpen}
                        onClose={() => setDeleteDialog({ isOpen: false, student: null })}
                        onConfirm={confirmDelete}
                        title="Delete Student"
                        message={`Are you sure you want to delete ${deleteDialog.student?.first_name} ${deleteDialog.student?.last_name}? This action cannot be undone.`}
                        confirmText="Delete"
                        type="danger"
                    />
                </div>
            </div>
        </MainLayout>
    );
}
