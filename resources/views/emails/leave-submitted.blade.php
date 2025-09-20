<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Leave Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .details { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
        .footer { background: #6c757d; color: white; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Leave Request Submitted</h1>
        </div>
        
        <div class="content">
            <p>Hello Admin,</p>
            <p>A new leave request has been submitted and requires your review.</p>
            
            <div class="details">
                <h3>Leave Request Details:</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Employee:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Email:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Leave Type:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->type }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Start Date:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->start_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>End Date:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->end_date->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Duration:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->duration }} day(s)</td>
                    </tr>
                    @if($leaveRequest->reason)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Reason:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->reason }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Submitted:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            
            <p>Please review this request and take appropriate action.</p>
            
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ url('/admin/leaves') }}" class="btn">Review Leave Requests</a>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from the Leave Management System.</p>
        </div>
    </div>
</body>
</html>
