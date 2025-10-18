<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Child's Code Club Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; }
        .btn { display: inline-block; background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; margin: 20px 0; }
        .btn:hover { background: #218838; }
        .access-code { background: #e9ecef; padding: 15px; border-radius: 5px; font-family: monospace; font-size: 18px; text-align: center; margin: 20px 0; }
        .footer { text-align: center; margin-top: 30px; color: #6c757d; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Code Club Report Ready</h1>
            <p>{{ $report->student->student_first_name }} {{ $report->student->student_last_name }}</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $report->student->student_parent_name ?? 'Parent/Guardian' }},</h2>
            
            <p>Great news! Your child's progress report for <strong>{{ $report->club->club_name }}</strong> is now available. We're excited to share {{ $report->student->student_first_name }}'s achievements and progress in coding!</p>
            
            <h3>📊 Overall Performance: {{ round($report->report_overall_score) }}%</h3>
            
            <p>The report includes detailed information about:</p>
            <ul>
                <li>📈 Assessment scores and progress</li>
                <li>🎯 Scratch projects and coding achievements</li>
                <li>📅 Attendance and participation</li>
                <li>💡 Areas of strength and growth opportunities</li>
            </ul>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $parent_access_url }}" class="btn">View Full Report</a>
            </div>
            
            <div class="access-code">
                <strong>Access Code:</strong><br>
                {{ $access_code }}
            </div>
            
            <p><strong>Important:</strong> Keep this access code safe. You'll need it to view your child's report.</p>
            
            <p>If you have any questions about the report or your child's progress, please don't hesitate to contact us.</p>
            
            <p>Thank you for supporting your child's coding journey!</p>
            
            <p>Best regards,<br>
            The Code Club Team</p>
        </div>
        
        <div class="footer">
            <p>This email was sent regarding {{ $report->student->student_first_name }} {{ $report->student->student_last_name }}'s participation in {{ $report->club->club_name }}.</p>
            <p>If you believe you received this email in error, please contact us immediately.</p>
        </div>
    </div>
</body>
</html>


