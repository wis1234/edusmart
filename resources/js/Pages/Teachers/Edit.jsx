import React, { useState, useEffect } from 'react';
import { Inertia } from '@inertiajs/inertia';

const Edit = ({ teacher, subjects, classRooms, schools }) => {
  const [formData, setFormData] = useState({
    first_name: teacher.teacher_firstname || '',
    last_name: teacher.teacher_lastname || '',
    email: teacher.teacher_email || '',
    phone: teacher.teacher_phone || '',
    date_of_birth: teacher.date_of_birth ? teacher.date_of_birth.substring(0, 10) : '',
    gender: teacher.gender || '',
    address: teacher.address || '',
    grade: teacher.grade || '',
    speciality: teacher.speciality || '',
    subject_title: teacher.subject_title || '',
    status: teacher.status || '',
    schools: teacher.school_id ? [teacher.school_id] : [],
    profile_photo: null,
    password: '',
    password_confirmation: '',
    subjects: [],
    class_rooms: [],
    years: [],
  });

  useEffect(() => {
    // Initialize teaching assignments from teacher data
    const initialSubjects = [];
    const initialClassRooms = [];
    const initialYears = [];

    if (teacher.taughtSubjects && teacher.teachingClassRooms) {
      teacher.taughtSubjects.forEach((subject) => {
        teacher.teachingClassRooms.forEach((classRoom) => {
          if (classRoom.pivot.subject_id === subject.id) {
            initialSubjects.push(subject.id);
            initialClassRooms.push(classRoom.id);
            initialYears.push(classRoom.pivot.year);
          }
        });
      });
    }

    if (initialSubjects.length === 0) {
      initialSubjects.push('');
      initialClassRooms.push('');
      initialYears.push(new Date().getFullYear());
    }

    setFormData((prev) => ({
      ...prev,
      subjects: initialSubjects,
      class_rooms: initialClassRooms,
      years: initialYears,
    }));
  }, [teacher]);

  const handleChange = (e) => {
    const { name, value, files } = e.target;
    if (name === 'profile_photo') {
      setFormData({ ...formData, profile_photo: files[0] });
    } else if (name.startsWith('subjects')) {
      const index = parseInt(name.split('.')[1]);
      const newSubjects = [...formData.subjects];
      newSubjects[index] = value;
      setFormData({ ...formData, subjects: newSubjects });
    } else if (name.startsWith('class_rooms')) {
      const index = parseInt(name.split('.')[1]);
      const newClassRooms = [...formData.class_rooms];
      newClassRooms[index] = value;
      setFormData({ ...formData, class_rooms: newClassRooms });
    } else if (name.startsWith('years')) {
      const index = parseInt(name.split('.')[1]);
      const newYears = [...formData.years];
      newYears[index] = value;
      setFormData({ ...formData, years: newYears });
    } else if (name === 'schools') {
      setFormData({ ...formData, schools: [value] });
    } else {
      setFormData({ ...formData, [name]: value });
    }
  };

  const addAssignment = () => {
    setFormData({
      ...formData,
      subjects: [...formData.subjects, ''],
      class_rooms: [...formData.class_rooms, ''],
      years: [...formData.years, new Date().getFullYear()],
    });
  };

  const removeAssignment = (index) => {
    const newSubjects = [...formData.subjects];
    const newClassRooms = [...formData.class_rooms];
    const newYears = [...formData.years];
    newSubjects.splice(index, 1);
    newClassRooms.splice(index, 1);
    newYears.splice(index, 1);
    setFormData({
      ...formData,
      subjects: newSubjects,
      class_rooms: newClassRooms,
      years: newYears,
    });
  };

    const handleSubmit = (e) => {
    e.preventDefault();
    const data = new FormData();
    Object.entries(formData).forEach(([key, value]) => {
      if (Array.isArray(value)) {
        value.forEach((v, i) => {
          data.append(`${key}[${i}]`, v);
        });
      } else if (value !== null) {
        data.append(key, value);
      }
    });
    Inertia.put(route('teachers.update', teacher.id), data, {
      preserveScroll: true,
    });
  };

  return (
    <div className="container mx-auto px-4">
      <h1 className="text-2xl font-bold mb-4">Edit Teacher</h1>
      <form onSubmit={handleSubmit} encType="multipart/form-data" className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        {/* Basic Info */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="col-span-2">
            <h2 className="text-xl font-semibold mb-3 border-b pb-2">Basic Information</h2>
          </div>
          <div className="mb-4">
            <label htmlFor="first_name" className="block font-semibold mb-1">First Name*</label>
            <input type="text" name="first_name" id="first_name" value={formData.first_name} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="last_name" className="block font-semibold mb-1">Last Name</label>
            <input type="text" name="last_name" id="last_name" value={formData.last_name} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="email" className="block font-semibold mb-1">Email Address*</label>
            <input type="email" name="email" id="email" value={formData.email} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="phone" className="block font-semibold mb-1">Phone Number*</label>
            <input type="tel" name="phone" id="phone" value={formData.phone} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="date_of_birth" className="block font-semibold mb-1">Date of Birth*</label>
            <input type="date" name="date_of_birth" id="date_of_birth" value={formData.date_of_birth} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="gender" className="block font-semibold mb-1">Gender*</label>
            <select name="gender" id="gender" value={formData.gender} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div className="col-span-2 mb-4">
            <label htmlFor="address" className="block font-semibold mb-1">Address*</label>
            <textarea name="address" id="address" rows="3" value={formData.address} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500"></textarea>
          </div>
          <div className="mb-4">
            <label htmlFor="grade" className="block font-semibold mb-1">Grade*</label>
            <input type="text" name="grade" id="grade" value={formData.grade} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="speciality" className="block font-semibold mb-1">Speciality*</label>
            <input type="text" name="speciality" id="speciality" value={formData.speciality} onChange={handleChange} required className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="subject_title" className="block font-semibold mb-1">Subject Title</label>
            <input type="text" name="subject_title" id="subject_title" value={formData.subject_title} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="status" className="block font-semibold mb-1">Status</label>
            <select name="status" id="status" value={formData.status} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
              <option value="">Select Status</option>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
              <option value="suspended">Suspended</option>
            </select>
          </div>
          <div className="mb-4">
            <label htmlFor="schools" className="block font-semibold mb-1">School</label>
            <select name="schools" id="schools" value={formData.schools[0] || ''} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
              <option value="">Select School</option>
              {schools.map((school) => (
                <option key={school.id} value={school.id}>{school.name}</option>
              ))}
            </select>
          </div>
          <div className="mb-4">
            <label htmlFor="profile_photo" className="block font-semibold mb-1">Profile Photo</label>
            <input type="file" name="profile_photo" id="profile_photo" onChange={handleChange} accept="image/*" className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="password" className="block font-semibold mb-1">Password</label>
            <input type="password" name="password" id="password" value={formData.password} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
          <div className="mb-4">
            <label htmlFor="password_confirmation" className="block font-semibold mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" value={formData.password_confirmation} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
          </div>
        </div>

        {/* Teaching Assignments */}
        <div className="mt-6">
          <h2 className="text-xl font-semibold mb-3 border-b pb-2">Teaching Assignments</h2>
          {formData.subjects.map((subjectId, index) => (
            <div key={index} className="grid grid-cols-4 gap-4 mb-4">
              <div>
                <label className="block font-semibold mb-1">Subject</label>
                <select name={`subjects.${index}`} value={subjectId} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                  <option value="">Select Subject</option>
                  {subjects.map((subject) => (
                    <option key={subject.id} value={subject.id}>{subject.name}</option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block font-semibold mb-1">Class Room</label>
                <select name={`class_rooms.${index}`} value={formData.class_rooms[index]} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500">
                  <option value="">Select Class Room</option>
                  {classRooms.map((classRoom) => (
                    <option key={classRoom.id} value={classRoom.id}>{classRoom.name}</option>
                  ))}
                </select>
              </div>
              <div>
                <label className="block font-semibold mb-1">Year</label>
                <input type="number" name={`years.${index}`} value={formData.years[index]} onChange={handleChange} className="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-500" />
              </div>
              <div className="flex items-end">
                <button type="button" onClick={() => removeAssignment(index)} className="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Remove</button>
              </div>
            </div>
          ))}
          <button type="button" onClick={addAssignment} className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Add Assignment</button>
        </div>

        <div className="mt-6">
          <button type="submit" className="bg-blue-600 text-white font-semibold px-6 py-2 rounded hover:bg-blue-700">Update Teacher</button>
        </div>
      </form>
    </div>
  );
};

export default Edit;
