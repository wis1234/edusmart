<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Evaluation Details Published</title>
</head>
<body>
    <h2>Evaluation Details</h2>
    <p>Dear Parent,</p>
    <p>Your child has received a new evaluation grade. Here are the details:</p>

    <table>
        <tr>
            <th>Subject</th>
            <td>{{ $studentGrade->evaluation->subject->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Class Room</th>
            <td>{{ $studentGrade->evaluation->classRoom->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Evaluation Type</th>
            <td>{{ $studentGrade->evaluation->evaluationType->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Teacher</th>
            <td>{{ $studentGrade->evaluation->teacher->user->full_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Academic Year</th>
            <td>{{ $studentGrade->evaluation->academic_year ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Term</th>
            <td>{{ $studentGrade->evaluation->term ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Evaluation Date</th>
            <td>{{ $studentGrade->evaluation->evaluation_date->format('F d, Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Total Marks</th>
            <td>{{ $studentGrade->evaluation->total_marks ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Passing Marks</th>
            <td>{{ $studentGrade->evaluation->passing_marks ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Grade</th>
            <td>{{ $studentGrade->marks_obtained }} / {{ $studentGrade->evaluation->total_marks }}</td>
        </tr>
        <tr>
            <th>Remarks</th>
            <td>{{ $studentGrade->remarks ?? 'No remarks' }}</td>
        </tr>
    </table>

    <p>Best regards,<br>EduSmart</p>
</body>
</html>
