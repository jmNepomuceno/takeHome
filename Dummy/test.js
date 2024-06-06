$(document).ready(function() {
    // Assuming 'data' is already defined and populated
    var data = [
        // Your data objects here
        {
            reference_num: '12345',
            index: '1',
            pat_full_name: 'John Doe',
            type: 'Emergency',
            type_color: '#ff0000',
            referred_by: 'Dr. Smith',
            landline_no: '123-456-7890',
            mobile_no: '098-765-4321',
            date_time: '2024-01-01 12:00:00',
            reception_time: '2024-01-01 12:05:00',
            sent_interdept_time: '2024-01-01 12:10:00',
            interdept_time: '2024-01-01 12:15:00',
            total_time: '00:15:00',
            approved_time: '2024-01-01 12:20:00',
            stopwatch: '00:10:00',
            status: 'Pending',
            hpercode: 'H12345',
            style_tr: 'background-color: #e0e0e0;',
        },
        // More data objects
    ];

    // Initialize the DataTable
    var dataTable = $('#myDataTable').DataTable({
        data: data,
        columns: [
            {
                title: 'Reference No.',
                render: function(data, type, row) {
                    return row.reference_num + '--' + row.index;
                }
            },
            { data: 'pat_full_name', title: "Patient's Name" },
            { 
                data: 'type', 
                title: 'Type',
                createdCell: function(td, cellData, rowData, row, col) {
                    if (rowData.type_color) {
                        $(td).css({
                            'background-color': rowData.type_color,
                            'text-align': 'center',
                            'font-weight': 'bold'
                        });
                    } 
                }
            },
            {
                data: 'referred_by', 
                title: 'Agency',
                render: function(data, type, row) {
                    return (
                        'Referred by: ' + row.referred_by + '<br>' +
                        'Landline: ' + row.landline_no + '<br>' +
                        'Mobile: ' + row.mobile_no
                    );
                },
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).css({
                        'font-size': '0.8rem'
                    });
                }
            },
            { 
                data: 'date_time', 
                title: 'Date/Time',
                render: function(data, type, row) {
                    return `
                        <div class="date-time-container">
                            Referred: ${row.date_time}<br>
                            Reception: ${row.reception_time}<br>
                            SDN Processed: ${row.sent_interdept_time}<br>
                            <i class="accordion-btn fa-solid fa-plus"></i>

                            <div class="breakdown-div">
                                <label class="interdept-proc-time-lbl">Interdept Processed: ${row.interdept_time}</label>
                                <label class="processed-time-lbl">Total Processed: ${row.total_time}</label>
                                <label>Approval: ${row.approved_time}</label>
                                <label>Deferral: 0000-00-00 00:00:00</label>
                                <label>Cancelled: 0000-00-00 00:00:00</label>
                                <label>Arrived: 0000-00-00 00:00:00</label>
                                <label>Checked: 0000-00-00 00:00:00</label>
                                <label>Admitted: 0000-00-00 00:00:00</label>
                                <label>Discharged: 0000-00-00 00:00:00</label>
                                <label>Follow up: 0000-00-00 00:00:00</label>
                                <label>Ref. Back: 0000-00-00 00:00:00</label>
                            </div>
                        </div>
                    `;
                },
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('id', 'dt-turnaround');
                }
            },
            { 
                data: 'stopwatch', 
                title: 'Response Time',
                render: function(data, type, row) {
                    return 'Processing: ' + data;
                },
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).css({
                        'text-align': 'center',
                    });
                }
            },
            { 
                data: 'status', 
                title: 'Status',
                render: function(data, type, row) {
                    return `
                        <div>
                            ${row.status}
                            <i class="pencil-btn fa-solid fa-pencil"></i>
                            <input class="hpercode" type="hidden" name="hpercode" value="${row.hpercode}">
                        </div>
                    `;
                },
                createdCell: function(td, cellData, rowData, row, col) {
                    $(td).attr('id', 'dt-status');
                    $(td).css({
                        'text-align': 'center',
                        'font-weight': 'bold',
                        'background-color': '#6b727f'
                    });
                }
            },
        ],
        pageLength: 5,
        responsive: true,
        processing: true,
        createdRow: function(row, data, dataIndex) {
            $(row).addClass('tr-incoming');
            if (data.style_tr) {
                $(row).attr('style', data.style_tr);
            }
        },
        initComplete: function(settings, json) {
            $('#myDataTable tbody').addClass('incoming-tbody');
            
            // Get total number of rows and store in a variable
            var dataTableLength = dataTable.rows().count();
            console.log('Total rows:', dataTableLength);

            // Now you can use this variable as needed
        }
    });
});