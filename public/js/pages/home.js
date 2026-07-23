$(function() {
    if (typeof window.LaravelData === 'undefined') {
        console.error('LaravelData not found!');
        return;
    }
    
    var dataEmployees = window.LaravelData.employees || [];
    var dataStats = window.LaravelData.statistics || {};
    var isAdmin = window.LaravelData.isAdmin || false;
    
    renderStats(dataStats);
    renderTable(dataEmployees);
    
    $('#btnReload').click(function() {
        $('#employeeCard').fadeOut(200, function() {
            $('#employeeCard').fadeIn(200);
        });
        $('.stat-card').fadeOut(200, function() {
            $(this).fadeIn(200);
        });
    });
    
    function renderStats(stats) {
        if (!stats) return;
        $('#sTotal').text(stats.total_employees || 0);
        $('#sDept').text(stats.total_departments || 0);
        $('#sPos').text(stats.total_positions || 0);
        $('#sAvg').text(formatMoney(stats.avg_salary || 0));
        $('#sU30').text(stats.under_30_count || 0);
    }
    
    function renderTable(employees) {
        var html = '';
        if (!employees || employees.length === 0) {
            html = '<tr><td colspan="9" style="text-align: center; padding: 20px; color: #666;">Chưa có dữ liệu</td></tr>';
        } else {
            $.each(employees, function(i, e) {
                var deptName = e.department ? e.department.name : 'N/A';
                var posName = e.position ? e.position.name : 'N/A';
                var birthday = e.birthday ? formatDate(e.birthday) : 'N/A';
                var actions = '';
                
                if (isAdmin) {
                    actions = '<a href="/employees/' + e.emp_id + '/edit" style="color: #007bff; text-decoration: none; margin-right: 10px;">Sửa</a>' +
                              '<form action="/employees/' + e.emp_id + '" method="POST" style="display: inline;">' +
                              '<input type="hidden" name="_token" value="' + window.LaravelData.csrfToken + '">' +
                              '<input type="hidden" name="_method" value="DELETE">' +
                              '<button type="button" onclick="if(confirm(\'Xóa nhân viên này?\')) { this.form.submit(); }" style="color: #dc3545; background: none; border: none; cursor: pointer; padding: 0;">Xóa</button>' +
                              '</form>';
                }
                
                html += '<tr style="border-bottom: 1px solid #ddd;">' +
                    '<td style="padding: 12px;"><strong>' + e.emp_id + '</strong></td>' +
                    '<td style="padding: 12px;">' + e.full_name + '</td>' +
                    '<td style="padding: 12px;">' + e.email + '</td>' +
                    '<td style="padding: 12px; text-align: center;">' + birthday + '</td>' +
                    '<td style="padding: 12px;">' + deptName + '</td>' +
                    '<td style="padding: 12px;">' + posName + '</td>' +
                    '<td style="padding: 12px; text-align: right;">' + formatMoney(e.base_salary) + '</td>' +
                    '<td style="padding: 12px; text-align: right;">' + formatMoney(e.actual_salary) + '</td>' +
                    '<td style="padding: 12px; text-align: center;">' + actions + '</td>' +
                    '</tr>';
            });
        }
        $('#tableBody').html(html);
    }
    
    function formatMoney(n) {
        if (!n && n !== 0) return '0 đ';
        return new Intl.NumberFormat('vi-VN').format(n) + ' đ';
    }
    
    function formatDate(dateStr) {
        if (!dateStr) return 'N/A';
        var parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        return parts[2] + '/' + parts[1] + '/' + parts[0];
    }
});
