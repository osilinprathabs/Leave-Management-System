<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Leave Request {{ ucfirst(strtolower($leaveRequest->status)) }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: {{ $leaveRequest->status == 'Approved' ? '#28a745' : ($leaveRequest->status == 'Rejected' ? '#dc3545' : '#ffc107') }}; color: white; padding: 20px; text-align: center; }
        .content { background: #f8f9fa; padding: 20px; }
        .details { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid {{ $leaveRequest->status == 'Approved' ? '#28a745' : ($leaveRequest->status == 'Rejected' ? '#dc3545' : '#ffc107') }}; }
        .footer { background: #6c757d; color: white; padding: 15px; text-align: center; font-size: 12px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .status-{{ strtolower($leaveRequest->status) }} { color: {{ $leaveRequest->status == 'Approved' ? '#28a745' : ($leaveRequest->status == 'Rejected' ? '#dc3545' : '#ffc107') }}; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Leave Request {{ ucfirst(strtolower($leaveRequest->status)) }}</h1>
        </div>
        
        <div class="content">
            <p>Hello {{ $leaveRequest->user->name }},</p>
            <p>Your leave request has been <span class="status-{{ strtolower($leaveRequest->status) }}">{{ $leaveRequest->status }}</span>.</p>
            
            <div class="details">
                <h3>Leave Request Details:</h3>
                <table style="width: 100%; border-collapse: collapse;">
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
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Status:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><span class="status-{{ strtolower($leaveRequest->status) }}">{{ $leaveRequest->status }}</span></td>
                    </tr>
                    @if($leaveRequest->reason)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Your Reason:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->reason }}</td>
                    </tr>
                    @endif
                    @if($leaveRequest->admin_remarks)
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Admin Remarks:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->admin_remarks }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;"><strong>Updated:</strong></td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{{ $leaveRequest->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            
            @if($leaveRequest->status == 'Approved')
                <p>Your leave request has been approved. Please plan accordingly.</p>
            @elseif($leaveRequest->status == 'Rejected')
                <p>Your leave request has been rejected. Please check the admin remarks above for more details.</p>
            @endif
            
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ url('/leaves') }}" class="btn">View My Leave History</a>
            </div>
        </div>
        
        <div class="footer">
            <p>This is an automated notification from the Leave Management System.</p>
        </div>
    </div>
</body>
</html>
