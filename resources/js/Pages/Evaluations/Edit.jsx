import React, { useState } from 'react';
import { Inertia } from '@inertiajs/inertia';
import { usePage, InertiaLink } from '@inertiajs/inertia-react';

const EvaluationsEdit = ({ evaluation, subjects, evaluationTypes, classRooms, teachers }) => {
  const { errors } = usePage().props;

  const [form, setForm] = useState({
    subject_id: evaluation.subject_id || '',
    class_room_id: evaluation.class_room_id || '',
    evaluation_type_id: evaluation.evaluation_type_id || '',
    academic_year: evaluation.academic_year || '',
    term: evaluation.term || '',
    evaluation_date: evaluation.evaluation_date || '',
    total_marks: evaluation.total_marks || '',
    passing_marks: evaluation.passing_marks || '',
    notes: evaluation.notes || '',
    teacher_id: evaluation.teacher_id || '',
  });

  const handleChange = (e) => {
    const { name, value } = e.target;
    setForm({
      ...form,
      [name]: value,
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    Inertia.post(`/evaluations/${evaluation.id}`, form, { method: 'put' });
  };

  return (
    <div>
      <h1>Edit Evaluation</h1>
      <InertiaLink href="/evaluations" className="btn btn-secondary mb-4">
        Back to Evaluations
      </InertiaLink>
      <form onSubmit={handleSubmit} className="max-w-lg">
        <div className="mb-4">
          <label className="block mb-1">Subject</label>
          <select
            name="subject_id"
            value={form.subject_id}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          >
            <option value="">Select Subject</option>
            {subjects.map((subject) => (
              <option key={subject.id} value={subject.id}>
                {subject.name}
              </option>
            ))}
          </select>
          {errors.subject_id && <div className="text-red-600">{errors.subject_id}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Class Room</label>
          <select
            name="class_room_id"
            value={form.class_room_id}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          >
            <option value="">Select Class Room</option>
            {classRooms.map((classRoom) => (
              <option key={classRoom.id} value={classRoom.id}>
                {classRoom.name}
              </option>
            ))}
          </select>
          {errors.class_room_id && <div className="text-red-600">{errors.class_room_id}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Evaluation Type</label>
          <select
            name="evaluation_type_id"
            value={form.evaluation_type_id}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          >
            <option value="">Select Evaluation Type</option>
            {evaluationTypes.map((type) => (
              <option key={type.id} value={type.id}>
                {type.name}
              </option>
            ))}
          </select>
          {errors.evaluation_type_id && <div className="text-red-600">{errors.evaluation_type_id}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Academic Year</label>
          <input
            type="text"
            name="academic_year"
            value={form.academic_year}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.academic_year && <div className="text-red-600">{errors.academic_year}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Term</label>
          <input
            type="text"
            name="term"
            value={form.term}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.term && <div className="text-red-600">{errors.term}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Evaluation Date</label>
          <input
            type="date"
            name="evaluation_date"
            value={form.evaluation_date}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.evaluation_date && <div className="text-red-600">{errors.evaluation_date}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Total Marks</label>
          <input
            type="number"
            name="total_marks"
            value={form.total_marks}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.total_marks && <div className="text-red-600">{errors.total_marks}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Passing Marks</label>
          <input
            type="number"
            name="passing_marks"
            value={form.passing_marks}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.passing_marks && <div className="text-red-600">{errors.passing_marks}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Notes</label>
          <textarea
            name="notes"
            value={form.notes}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.notes && <div className="text-red-600">{errors.notes}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Teacher</label>
          <select
            name="teacher_id"
            value={form.teacher_id}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          >
            <option value="">Select Teacher</option>
            {teachers.map((teacher) => (
              <option key={teacher.id} value={teacher.id}>
                {teacher.user?.first_name} {teacher.user?.last_name}
              </option>
            ))}
          </select>
          {errors.teacher_id && <div className="text-red-600">{errors.teacher_id}</div>}
        </div>
        <button type="submit" className="btn btn-primary">
          Update Evaluation
        </button>
      </form>
    </div>
  );
};

export default EvaluationsEdit;
