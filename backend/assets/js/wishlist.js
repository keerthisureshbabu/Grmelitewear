(function () {
    'use strict'

    let checkAll = document.querySelector('.check-all');
    checkAll.addEventListener('click', checkAllFn)

    function checkAllFn() {
        if (checkAll.checked) {
            document.querySelectorAll('.wishlist-checkbox input').forEach(function (e) {
                e.closest('.wishlist-list').classList.add('selected');
                e.checked = true;
            });
        }
        else {
            document.querySelectorAll('.wishlist-checkbox input').forEach(function (e) {
                e.closest('.wishlist-list').classList.remove('selected');
                e.checked = false;
            });
        }
    }

    //delete Btn
    let wishlistbtn = document.querySelectorAll(".wishlist-btn");

    wishlistbtn.forEach((eleBtn) => {
        eleBtn.onclick = () => {
            let wishlist = eleBtn.closest(".wishlist-list")
            wishlist.remove();
        }
    })

})();