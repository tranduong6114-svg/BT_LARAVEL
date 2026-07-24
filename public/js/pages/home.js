$(function() {
    if (typeof window.LaravelData === 'undefined') {
        console.error('LaravelData not found!');
        return;
    }
    
    var csrfToken = window.LaravelData.csrfToken;
    var isAdmin = window.LaravelData.isAdmin || false;
    var pagination = window.LaravelData.pagination || { current_page: 1, per_page: 10, last_page: 1 };
    var pendingDeleteId = null;
    
    function loadEmployees(page = 1) {
        $.ajax({
            url: '/api/employees',
            method: 'GET',
            data: {
                page: page,
                per_page: pagination.per_page
            },
            success: function(response) {
                if (response.success) {
                    renderTable(response.employees);
                    renderPagination(response.pagination);
                    renderStats(response.statistics);
                    pagination = response.pagination;
                }
            },
            error: function() {
                showResultModal(false, 'Lỗi tải dữ liệu!');
            }
        });
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
                              '<button type="button" class="btn-delete" data-id="' + e.emp_id + '" data-name="' + e.full_name + '" style="color: #dc3545; background: none; border: none; cursor: pointer; padding: 0;">Xóa</button>';
                }
                
                html += '<tr id="row-' + e.emp_id + '" style="border-bottom: 1px solid #ddd;">' +
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
    
    function renderPagination(p) {
        if (!p || p.last_page <= 1) {
            $('#pagination').html('');
            return;
        }
        
        var html = '';
        var current = p.current_page;
        var last = p.last_page;
        
        if (current > 1) {
            html += '<button class="page-btn" data-page="' + (current - 1) + '" style="padding: 8px 12px; background: #f8f9fa; border: 1px solid #ddd; cursor: pointer;">«</button>';
        }
        
        for (var i = 1; i <= last; i++) {
            if (i === 1 || i === last || (i >= current - 2 && i <= current + 2)) {
                var active = (i === current) ? 'background: #007bff; color: white; border-color: #007bff;' : '';
                html += '<button class="page-btn" data-page="' + i + '" style="padding: 8px 12px; background: #f8f9fa; border: 1px solid #ddd; cursor: pointer; ' + active + '">' + i + '</button>';
            } else if (i === current - 3 || i === current + 3) {
                html += '<span style="padding: 8px;">...</span>';
            }
        }
        
        if (current < last) {
            html += '<button class="page-btn" data-page="' + (current + 1) + '" style="padding: 8px 12px; background: #f8f9fa; border: 1px solid #ddd; cursor: pointer;">»</button>';
        }
        
        html += '<span style="margin-left: 15px; color: #666;">Trang ' + current + '/' + last + ' (' + p.total + ' bản ghi)</span>';
        
        $('#pagination').html(html);
    }
    
    $(document).on('click', '.page-btn', function() {
        var page = $(this).data('page');
        if (page) {
            loadEmployees(page);
            $('html, body').animate({ scrollTop: 0 }, 300);
        }
    });
    
    $('#btnReload').click(function() {
        loadEmployees(pagination.current_page);
    });
    
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        pendingDeleteId = id;
        $('#modalDeleteText').html('Bạn có chắc muốn xóa nhân viên <strong>' + name + '</strong>?<br>Hành động này không thể hoàn tác.');
        $('#modalDeleteConfirm').css('display', 'flex');
    });
    
    $('#btnCancelDelete').click(function() {
        $('#modalDeleteConfirm').hide();
        pendingDeleteId = null;
    });
    
    $('#btnConfirmDelete').click(function() {
        if (!pendingDeleteId) return;
        var idToDelete = pendingDeleteId;
        $('#modalDeleteConfirm').hide();
        
        $.ajax({
            url: '/api/employees/' + idToDelete,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $('#row-' + idToDelete).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#tableBody tr').length === 0) {
                            loadEmployees(pagination.current_page > 1 ? pagination.current_page - 1 : 1);
                        }
                    });
                    if (response.statistics) {
                        renderStats(response.statistics);
                    }
                    setTimeout(function() {
                        showResultModal(true, response.message);
                    }, 350);
                } else {
                    showResultModal(false, response.message);
                }
            },
            error: function(xhr) {
                var msg = 'Xóa thất bại!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showResultModal(false, msg);
            },
            complete: function() {
                pendingDeleteId = null;
            }
        });
    });
    
    $('#btnCloseResult').click(function() {
        $('#modalResult').hide();
    });
    
    $('#modalDeleteConfirm, #modalResult').click(function(e) {
        if (e.target === this) {
            $(this).hide();
            pendingDeleteId = null;
        }
    });
    
    function showResultModal(success, message) {
        if (success) {
            $('#modalResultTitle').html('Thành công').css('color', '#28a745');
        } else {
            $('#modalResultTitle').html('Thất bại').css('color', '#dc3545');
        }
        $('#modalResultText').text(message);
        $('#modalResult').css('display', 'flex');
    }
    
    function renderStats(stats) {
        if (!stats) return;
        $('#sTotal').text(stats.total_employees || 0);
        $('#sDept').text(stats.total_departments || 0);
        $('#sPos').text(stats.total_positions || 0);
        $('#sAvg').text(formatMoney(stats.avg_salary || 0));
        $('#sU30').text(stats.under_30_count || 0);
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
    
    loadEmployees(1);
});