import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

const EvaluationsShow = ({ evaluation }) => {
  return (
    <div>
      <h1>Evaluation Details</h1>
      <InertiaLink href="/evaluations" className="btn btn-secondary mb-4">
        Back to Evaluations
      </InertiaLink>
      <div className="border p-4 rounded shadow">
        <p><strong>Subject:</strong> {evaluation.subject?.name}</p>
        <p><strong>Class Room:</strong> {evaluation.classRoom?.name}</p>
        <p><strong>Evaluation Type:</strong> {evaluation.evaluationType?.name}</p>
        <p><strong>Academic Year:</strong> {evaluation.academic_year}</p>
        <p><strong>Term:</strong> {evaluation.term}</p>
        <p><strong>Evaluation Date:</strong> {evaluation.evaluation_date}</p>
        <p><strong>Total Marks:</strong> {evaluation.total_marks}</p>
        <p><strong>Passing Marks:</strong> {evaluation.passing_marks}</p>
        <p><strong>Notes:</strong> {evaluation.notes}</p>
        <p><strong>Teacher:</strong> {evaluation.teacher?.teacher_firstname} {evaluation.teacher?.teacher_lastname}</p>
      </div>
      <InertiaLink href={`/evaluations/${evaluation.id}/edit`} className="btn btn-primary mt-4">
        Edit Evaluation
      </InertiaLink>
    </div>
  );
};

export default EvaluationsShow;
