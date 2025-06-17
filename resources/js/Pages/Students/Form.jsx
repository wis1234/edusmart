import React, { useState, useRef } from 'react';
import { useForm, Link } from '@inertiajs/react';
import Input from '@/Components/UI/Input';
import Button from '@/Components/UI/Button';
import { 
    UserIcon, 
    ArrowLeftIcon,
    PhotoIcon,
    XMarkIcon
} from '@heroicons/react/24/outline';

const commonMedicalConditions = [
    { id: 'asthma', label: 'Asthma' },
    { id: 'diabetes', label: 'Diabetes' },
    { id: 'allergies', label: 'Allergies' },
    { id: 'heart_condition', label: 'Heart Condition' },
    { id: 'epilepsy', label: 'Epilepsy' },
    { id: 'adhd', label: 'ADHD' },
    { id: 'vision_problems', label: 'Vision Problems' },
    { id: 'hearing_problems', label: 'Hearing Problems' },
];

export default function StudentForm({ student = null, classRooms, schools, parents }) {
    const [photoPreview, setPhotoPreview] = useState(student?.profile_photo ? `/storage/${student.profile_photo}` : null);
    const fileInputRef = useRef();
    
    const { data, setData, post, put, processing, errors, progress, reset } = useForm({
        first_name: student?.first_name || '',
        last_name: student?.last_name || '',
        admission_number: student?.admission_number || '',
        roll_number: student?.roll_number || '',
        admission_date: student?.admission_date ? new Date(student.admission_date).toISOString().split('T')[0] : '',
        date_of_birth: student?.date_of_birth ? new Date(student.date_of_birth).toISOString().split('T')[0] : '',
        gender: student?.gender || '',
        blood_group: student?.blood_group || '',
        address: student?.address || '',
        emergency_contact: student?.emergency_contact || '',
        medical_conditions: student?.medical_conditions || '',
        academic_year: student?.academic_year || new Date().getFullYear().toString(),
        status: student?.status || 'active',
        class_room_id: student?.class_room_id || '',
        school_id: student?.school_id || '',
        parent_id: student?.parent_id || '',
        profile_photo: null,
    });

    const [selectedMedicalConditions, setSelectedMedicalConditions] = useState(
        student?.medical_conditions ? student.medical_conditions.split(', ') : []
    );
    const [otherMedicalCondition, setOtherMedicalCondition] = useState('');
    const [activeSection, setActiveSection] = useState('all'); // 'all', 'basic', 'academic', 'personal', 'medical'
    const [dirtyFields, setDirtyFields] = useState(new Set());

    const handleFieldChange = (field, value) => {
        setData(field, value);
        setDirtyFields(prev => new Set([...prev, field]));
    };

    const handleMedicalConditionToggle = (condition) => {
        setSelectedMedicalConditions(prev => {
            const newConditions = prev.includes(condition)
                ? prev.filter(c => c !== condition)
                : [...prev, condition];
            
            const allConditions = [...newConditions];
            if (otherMedicalCondition) {
                allConditions.push(otherMedicalCondition);
            }
            
            handleFieldChange('medical_conditions', allConditions.join(', '));
            return newConditions;
        });
    };

    const handleOtherMedicalConditionChange = (e) => {
        const value = e.target.value;
        setOtherMedicalCondition(value);
        
        const allConditions = [...selectedMedicalConditions];
        if (value) {
            allConditions.push(value);
        }
        
        handleFieldChange('medical_conditions', allConditions.join(', '));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        
        // For partial updates, only send changed fields
        if (student) {
            const changedData = {};
            dirtyFields.forEach(field => {
                changedData[field] = data[field];
            });
            
            put(route('students.update', student.id), changedData, {
                onSuccess: () => {
                    // Handle success
                },
                preserveScroll: true,
            });
        } else {
            post(route('students.store'), {
                onSuccess: () => {
                    reset();
                    setPhotoPreview(null);
                    setSelectedMedicalConditions([]);
                    setOtherMedicalCondition('');
                },
            });
        }
    };

    const handlePhotoChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size should not exceed 2MB');
                return;
            }
            if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                alert('Please upload an image file (JPEG, PNG, GIF)');
                return;
            }
            setData('profile_photo', file);
            setPhotoPreview(URL.createObjectURL(file));
        }
    };

    const removePhoto = () => {
        setData('profile_photo', null);
        setPhotoPreview(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    return (
        <div className="py-6">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Header */}
                <div className="flex justify-between items-center mb-6">
                    <div className="flex items-center space-x-3">
                        <Link
                            href={route('students.index')}
                            className="inline-flex items-center text-gray-500 hover:text-gray-700"
                        >
                            <ArrowLeftIcon className="h-5 w-5 mr-1" />
                            <span>Back</span>
                        </Link>
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900">
                                {student ? 'Edit Student' : 'Add New Student'}
                            </h2>
                            <p className="mt-1 text-sm text-gray-500">
                                {student ? 'Update student information' : 'Create a new student profile'}
                            </p>
                        </div>
                    </div>
                </div>

                <div className="mb-6 flex space-x-4 border-b border-gray-200">
                    <button
                        type="button"
                        onClick={() => setActiveSection('all')}
                        className={`px-4 py-2 text-sm font-medium ${
                            activeSection === 'all'
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        All Information
                    </button>
                    <button
                        type="button"
                        onClick={() => setActiveSection('basic')}
                        className={`px-4 py-2 text-sm font-medium ${
                            activeSection === 'basic'
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Basic Info
                    </button>
                    <button
                        type="button"
                        onClick={() => setActiveSection('academic')}
                        className={`px-4 py-2 text-sm font-medium ${
                            activeSection === 'academic'
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Academic
                    </button>
                    <button
                        type="button"
                        onClick={() => setActiveSection('personal')}
                        className={`px-4 py-2 text-sm font-medium ${
                            activeSection === 'personal'
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Personal
                    </button>
                    <button
                        type="button"
                        onClick={() => setActiveSection('medical')}
                        className={`px-4 py-2 text-sm font-medium ${
                            activeSection === 'medical'
                                ? 'border-b-2 border-blue-500 text-blue-600'
                                : 'text-gray-500 hover:text-gray-700 hover:border-gray-300'
                        }`}
                    >
                        Medical
                    </button>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="bg-white shadow-sm rounded-lg overflow-hidden">
                        {/* Profile Photo Section */}
                        <div className="p-6 border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900">Profile Photo</h3>
                            <div className="mt-4 flex items-center space-x-6">
                                <div className="relative">
                                    {photoPreview ? (
                                        <>
                                            <img 
                                                src={photoPreview} 
                                                alt="Preview" 
                                                className="h-32 w-32 rounded-full object-cover ring-4 ring-white"
                                            />
                                            <button
                                                type="button"
                                                onClick={removePhoto}
                                                className="absolute -top-2 -right-2 rounded-full bg-red-100 p-1 text-red-600 hover:bg-red-200"
                                            >
                                                <XMarkIcon className="h-4 w-4" />
                                            </button>
                                        </>
                                    ) : (
                                        <div className="h-32 w-32 rounded-full bg-gray-100 flex items-center justify-center ring-4 ring-white">
                                            <UserIcon className="h-16 w-16 text-gray-400" />
                                        </div>
                                    )}
                                </div>
                                <div className="flex-1">
                                    <label
                                        htmlFor="photo-upload"
                                        className="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none"
                                    >
                                        <div className="flex items-center space-x-2">
                                            <PhotoIcon className="h-6 w-6" />
                                            <span>Upload new photo</span>
                                        </div>
                                        <input
                                            id="photo-upload"
                                            ref={fileInputRef}
                                            type="file"
                                            className="sr-only"
                                            onChange={handlePhotoChange}
                                            accept="image/*"
                                        />
                                    </label>
                                    <p className="mt-1 text-sm text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                    {errors.profile_photo && (
                                        <p className="mt-1 text-sm text-red-600">{errors.profile_photo}</p>
                                    )}
                                    {progress && (
                                        <div className="mt-2 w-full max-w-xs">
                                            <div className="bg-gray-200 rounded-full h-2.5">
                                                <div
                                                    className="bg-blue-600 h-2.5 rounded-full"
                                                    style={{ width: `${progress}%` }}
                                                />
                                            </div>
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>

                        {(activeSection === 'all' || activeSection === 'basic') && (
                            <div className="p-6 border-b border-gray-200">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <Input
                                            label="First Name"
                                            value={data.first_name}
                                            onChange={e => setData('first_name', e.target.value)}
                                            error={errors.first_name}
                                            required
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            label="Last Name"
                                            value={data.last_name}
                                            onChange={e => setData('last_name', e.target.value)}
                                            error={errors.last_name}
                                            required
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            label="Admission Number"
                                            value={data.admission_number}
                                            onChange={e => setData('admission_number', e.target.value)}
                                            error={errors.admission_number}
                                            required
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            label="Roll Number"
                                            value={data.roll_number}
                                            onChange={e => setData('roll_number', e.target.value)}
                                            error={errors.roll_number}
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        {(activeSection === 'all' || activeSection === 'academic') && (
                            <div className="p-6 border-b border-gray-200">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Academic Information</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">School</label>
                                        <select
                                            value={data.school_id}
                                            onChange={e => setData('school_id', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Select School</option>
                                            {schools.map(school => (
                                                <option key={school.id} value={school.id}>
                                                    {school.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.school_id && (
                                            <p className="mt-1 text-sm text-red-600">{errors.school_id}</p>
                                        )}
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Class</label>
                                        <select
                                            value={data.class_room_id}
                                            onChange={e => setData('class_room_id', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        >
                                            <option value="">Select Class</option>
                                            {classRooms.map(classRoom => (
                                                <option key={classRoom.id} value={classRoom.id}>
                                                    {classRoom.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.class_room_id && (
                                            <p className="mt-1 text-sm text-red-600">{errors.class_room_id}</p>
                                        )}
                                    </div>
                                    <div>
                                        <Input
                                            type="date"
                                            label="Admission Date"
                                            value={data.admission_date}
                                            onChange={e => setData('admission_date', e.target.value)}
                                            error={errors.admission_date}
                                            required
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            label="Academic Year"
                                            type="number"
                                            min={2000}
                                            max={2100}
                                            value={data.academic_year}
                                            onChange={e => setData('academic_year', e.target.value)}
                                            error={errors.academic_year}
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        {(activeSection === 'all' || activeSection === 'personal') && (
                            <div className="p-6 border-b border-gray-200">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <Input
                                            type="date"
                                            label="Date of Birth"
                                            value={data.date_of_birth}
                                            onChange={e => setData('date_of_birth', e.target.value)}
                                            error={errors.date_of_birth}
                                            required
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Gender</label>
                                        <select
                                            value={data.gender}
                                            onChange={e => setData('gender', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                            required
                                        >
                                            <option value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                        {errors.gender && (
                                            <p className="mt-1 text-sm text-red-600">{errors.gender}</p>
                                        )}
                                    </div>
                                    <div>
                                        <Input
                                            label="Blood Group"
                                            value={data.blood_group}
                                            onChange={e => setData('blood_group', e.target.value)}
                                            error={errors.blood_group}
                                            placeholder="e.g., A+, B-, O+"
                                        />
                                    </div>
                                    <div>
                                        <Input
                                            label="Emergency Contact"
                                            type="tel"
                                            value={data.emergency_contact}
                                            onChange={e => setData('emergency_contact', e.target.value)}
                                            error={errors.emergency_contact}
                                            placeholder="Emergency contact number"
                                        />
                                    </div>
                                    <div className="md:col-span-2">
                                        <Input
                                            label="Address"
                                            value={data.address}
                                            onChange={e => setData('address', e.target.value)}
                                            error={errors.address}
                                            type="textarea"
                                            rows={3}
                                        />
                                    </div>
                                </div>
                            </div>
                        )}

                        {(activeSection === 'all' || activeSection === 'medical') && (
                            <div className="p-6 border-b border-gray-200">
                                <h3 className="text-lg font-medium text-gray-900 mb-4">Medical Information</h3>
                                <div className="space-y-4">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700 mb-2">
                                            Medical Conditions
                                        </label>
                                        <div className="grid grid-cols-2 gap-4 mb-4">
                                            {commonMedicalConditions.map(condition => (
                                                <div key={condition.id} className="flex items-center">
                                                    <input
                                                        type="checkbox"
                                                        id={condition.id}
                                                        checked={selectedMedicalConditions.includes(condition.label)}
                                                        onChange={() => handleMedicalConditionToggle(condition.label)}
                                                        className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    />
                                                    <label htmlFor={condition.id} className="ml-2 block text-sm text-gray-900">
                                                        {condition.label}
                                                    </label>
                                                </div>
                                            ))}
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                                Other Medical Conditions
                                            </label>
                                            <Input
                                                type="text"
                                                value={otherMedicalCondition}
                                                onChange={handleOtherMedicalConditionChange}
                                                placeholder="Enter other medical conditions"
                                            />
                                        </div>
                                    </div>
                                    {errors.medical_conditions && (
                                        <p className="mt-1 text-sm text-red-600">{errors.medical_conditions}</p>
                                    )}
                                </div>
                            </div>
                        )}

                        {/* Parent Information */}
                        <div className="p-6 border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Parent Information</h3>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Parent</label>
                                <select
                                    value={data.parent_id}
                                    onChange={e => setData('parent_id', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                >
                                    <option value="">Select Parent</option>
                                    {parents.map(parent => (
                                        <option key={parent.id} value={parent.id}>
                                            {parent.first_name} {parent.last_name}
                                        </option>
                                    ))}
                                </select>
                                {errors.parent_id && (
                                    <p className="mt-1 text-sm text-red-600">{errors.parent_id}</p>
                                )}
                            </div>
                        </div>

                        {/* Status */}
                        <div className="p-6 border-b border-gray-200">
                            <h3 className="text-lg font-medium text-gray-900 mb-4">Status</h3>
                            <div>
                                <label className="block text-sm font-medium text-gray-700">Student Status</label>
                                <select
                                    value={data.status}
                                    onChange={e => setData('status', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="graduated">Graduated</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                                {errors.status && (
                                    <p className="mt-1 text-sm text-red-600">{errors.status}</p>
                                )}
                            </div>
                        </div>

                        {/* Form Actions */}
                        <div className="px-6 py-4 bg-gray-50 flex items-center justify-end space-x-3">
                            <Button
                                type="button"
                                variant="secondary"
                                onClick={() => window.history.back()}
                            >
                                Cancel
                            </Button>
                            <Button
                                type="submit"
                                disabled={processing}
                            >
                                {processing ? 'Saving...' : (student ? 'Update Student' : 'Create Student')}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    );
} 