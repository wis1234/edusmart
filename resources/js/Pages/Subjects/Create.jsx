import React, { useState } from 'react';
import { Inertia } from '@inertiajs/inertia';
import { usePage, InertiaLink } from '@inertiajs/inertia-react';

const SubjectsCreate = () => {
  const { errors } = usePage().props;

  const [form, setForm] = useState({
    name: '',
    code: '',
    description: '',
    credits: '',
    level: '',
    hours_per_week: '',
    is_active: false,
  });

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm({
      ...form,
      [name]: type === 'checkbox' ? checked : value,
    });
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    Inertia.post('/subjects', form);
  };

  return (
    <div>
      <h1>Create Subject</h1>
      <InertiaLink href="/subjects" className="btn btn-secondary mb-4">
        Back to Subjects
      </InertiaLink>
      <form onSubmit={handleSubmit} className="max-w-lg">
        <div className="mb-4">
          <label className="block mb-1">Name</label>
          <input
            type="text"
            name="name"
            value={form.name}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.name && <div className="text-red-600">{errors.name}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Code</label>
          <input
            type="text"
            name="code"
            value={form.code}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.code && <div className="text-red-600">{errors.code}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Description</label>
          <textarea
            name="description"
            value={form.description}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.description && <div className="text-red-600">{errors.description}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Credits</label>
          <input
            type="number"
            name="credits"
            value={form.credits}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.credits && <div className="text-red-600">{errors.credits}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Level</label>
          <input
            type="text"
            name="level"
            value={form.level}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.level && <div className="text-red-600">{errors.level}</div>}
        </div>
        <div className="mb-4">
          <label className="block mb-1">Hours per Week</label>
          <input
            type="number"
            name="hours_per_week"
            value={form.hours_per_week}
            onChange={handleChange}
            className="w-full border border-gray-300 rounded px-3 py-2"
          />
          {errors.hours_per_week && <div className="text-red-600">{errors.hours_per_week}</div>}
        </div>
        <div className="mb-4">
          <label className="inline-flex items-center">
            <input
              type="checkbox"
              name="is_active"
              checked={form.is_active}
              onChange={handleChange}
              className="form-checkbox"
            />
            <span className="ml-2">Active</span>
          </label>
          {errors.is_active && <div className="text-red-600">{errors.is_active}</div>}
        </div>
        <button type="submit" className="btn btn-primary">
          Create Subject
        </button>
      </form>
    </div>
  );
};

export default SubjectsCreate;
