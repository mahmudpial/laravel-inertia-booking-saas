<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Booking Notification</title>
</head>

<body
    style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; bg-color: #f4f5f7; margin: 0; padding: 30px; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" width="100%"
        style="max-w: 600px; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px border-color: #eef2f5;">
        <tr>
            <td style="padding: 40px 30px; text-align: center; background: #4f46e5;">
                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: -0.5px;">
                    {{ $booking->business->name }}
                </h1>
            </td>
        </tr>

        <tr>
            <td style="padding: 30px;">
                @if($recipientType === 'admin')
                    <p style="font-size: 16px; color: #1f2937; margin-top: 0;">Hello Admin,</p>
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">A new appointment reservation has been
                        registered on your portal. Please review the matrix particulars below to update its schedule status:
                    </p>
                @else
                    <p style="font-size: 16px; color: #1f2937; margin-top: 0;">Dear Customer,</p>
                    <p style="font-size: 15px; color: #4b5563; line-height: 1.6;">Thank you for choosing us! Here is the
                        live tracking status update regarding your recent appointment arrangement:</p>
                @endif

                <div style="margin: 25px 0; padding: 15px 20px; border-radius: 6px; font-weight: 600; font-size: 15px; text-align: center;
                    @if($booking->status === 'confirmed') background-color: #ecfdf5; color: #065f46; border-left: 4px solid #10b981;
                    @elseif($booking->status === 'cancelled') background-color: #fef2f2; color: #991b1b; border-left: 4px solid #ef4444;
                    @else background-color: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b; @endif">
                    Current Status: {{ strtoupper($booking->status) }}
                </div>

                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
                    style="border-collapse: collapse; margin-top: 20px;">
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; font-size: 14px; color: #6b7280; font-weight: 500;" width="35%">
                            Service:</td>
                        <td style="padding: 12px 0; font-size: 14px; color: #1f2937; font-weight: 600;">
                            {{ $booking->service->name }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; font-size: 14px; color: #6b7280; font-weight: 500;">Date Calendar:
                        </td>
                        <td style="padding: 12px 0; font-size: 14px; color: #1f2937; font-weight: 600;">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 12px 0; font-size: 14px; color: #6b7280; font-weight: 500;">Time Window:
                        </td>
                        <td style="padding: 12px 0; font-size: 14px; color: #1f2937; font-weight: 600;">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                        </td>
                    </tr>
                    @if($booking->notes)
                        <tr>
                            <td style="padding: 12px 0; font-size: 14px; color: #6b7280; font-weight: 500;">Your Notes:</td>
                            <td style="padding: 12px 0; font-size: 14px; color: #4b5563; font-style: italic;">
                                "{{ $booking->notes }}"</td>
                        </tr>
                    @endif
                </table>

                <div style="margin-top: 35px; text-align: center;">
                    <a href="{{ url('/') }}"
                        style="background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 12px 30px; border-radius: 6px; font-size: 15px; font-weight: 600; display: inline-block; shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Go to Dashboard
                    </a>
                </div>
            </td>
        </tr>

        <tr>
            <td
                style="padding: 25px 30px; background: #f9fafb; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #eef2f5;">
                This is an automated system dispatch notification transmission from {{ $booking->business->name }}. <br>
                If you have questions regarding timelines, please contact your service desk venue directly.
            </td>
        </tr>
    </table>
</body>

</html>