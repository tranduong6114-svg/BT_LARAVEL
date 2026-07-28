import $ from 'jquery';

(function() {
    if ($('#btnDeleteEmployee').length === 0) return;

    var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    var isDeleting = false;

    $('#btnDeleteEmployee').click(function(e) {
        e.preventDefault();
        var name = $(this).data('name');
        $('#modalDeleteText').html('Bạn có chắc muốn xóa nhân viên <strong>' + name + '</strong>?<br>Hành động này không thể hoàn tác.');
        $('#modalDeleteConfirm').data('deleteId', $(this).data('id')).css('display', 'flex');
    });

    $('#btnCancelDelete').click(function() {
        $('#modalDeleteConfirm').hide();
    });

    $('#btnConfirmDelete').click(function() {
        if (isDeleting) return;
        
        var idToDelete = $('#modalDeleteConfirm').data('deleteId');
        if (!idToDelete) {
            $('#modalDeleteConfirm').hide();
            return;
        }
        
        isDeleting = true;
        $('#modalDeleteConfirm').hide();
        $('#btnConfirmDelete').prop('disabled', true).text('Đang xử lý...');
        
        $.ajax({
            url: '/api/employees/' + idToDelete,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#btnConfirmDelete').prop('disabled', false).text('Xác nhận xóa');
                if (response.success) {
                    showResultModal(true, response.message);
                    setTimeout(function() {
                        window.location.href = '/';
                    }, 1500);
                } else {
                    isDeleting = false;
                    showResultModal(false, response.message);
                }
            },
            error: function(xhr) {
                isDeleting = false;
                $('#btnConfirmDelete').prop('disabled', false).text('Xác nhận xóa');
                var msg = 'Xóa thất bại!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showResultModal(false, msg);
            }
        });
    });

    $('#btnCloseResult').click(function() {
        $('#modalResult').hide();
    });

    $('#modalDeleteConfirm, #modalResult').click(function(e) {
        if (e.target === this) {
            $(this).hide();
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
})();
