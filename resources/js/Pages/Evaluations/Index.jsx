import React, { useState } from 'react';
import { InertiaLink, usePage } from '@inertiajs/inertia-react';

const EvaluationsIndex = ({ evaluations }) => {
  const [search, setSearch] = useState('');
  const { errors } = usePage().props;

  const handleSearchChange = (e) => {
    setSearch(e.target.value);
  };

  const handleSearchSubmit = (e) => {
    e.preventDefault();
    // Implement search submission via Inertia or form submission
  };

  return (
    <div>
      <h1>Evaluations</h1>
      <InertiaLink href="/evaluations/create" className="btn btn-primary mb-4">
        Create New Evaluation
      </InertiaLink>
      <form onSubmit={handleSearchSubmit} className="mb-4">
        <input
          type="text"
          value={search}
          onChange={handleSearchChange}
          placeholder="Search evaluations..."
          className="border border-gray-300 rounded px-3 py-2 w-64"
        />
        <button type="submit" className="btn btn-secondary ml-2">
          Search
        </button>
      </form>
      <table className="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr>
            <th className="border border-gray-300 px-4 py-2">Subject</th>
            <th className="border border-gray-300 px-4 py-2">Class Room</th>
            <th className="border border-gray-300 px-4 py-2">Evaluation Type</th>
            <th className="border border-gray-300 px-4 py-2">Academic Year</th>
            <th className="border border-gray-300 px-4 py-2">Term</th>
            <th className="border border-gray-300 px-4 py-2">Evaluation Date</th>
            <th className="border border-gray-300 px-4 py-2">Teacher</th>
            <th className="border border-gray-300 px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {evaluations.data.map((evaluation) => (
            <tr key={evaluation.id}>
              <td className="border border-gray-300 px-4 py-2">{evaluation.subject?.name}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.classRoom?.name}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.evaluationType?.name}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.academic_year}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.term}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.evaluation_date}</td>
              <td className="border border-gray-300 px-4 py-2">{evaluation.teacher?.teacher_firstname} {evaluation.teacher?.teacher_lastname}</td>
              <td className="border border-gray-300 px-4 py-2">
                <InertiaLink href={`/evaluations/${evaluation.id}`} className="text-blue-600 hover:underline mr-2">
                  View
                </InertiaLink>
                <InertiaLink href={`/evaluations/${evaluation.id}/edit`} className="text-green-600 hover:underline">
                  Edit
                </InertiaLink>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default EvaluationsIndex;
