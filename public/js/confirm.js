window.confirmConfig = {
    create: {
        title: "ایجاد اطلاعات",
        text: "آیا از ایجاد :item اطمینان دارید؟",
        icon: "question",
        confirmButtonText: "بله، ثبت شود",
    },
    edit: {
        title: "ویرایش اطلاعات",
        text: "آیا از ویرایش :item اطمینان دارید؟",
        icon: "warning",
        confirmButtonText: "بله، ذخیره شود",
    },
    delete: {
        title: "حذف اطلاعات",
        text: "آیا از حذف :item اطمینان دارید؟ این اقدام قابل بازگشت نیست.",
        icon: "error",
        confirmButtonText: "بله، حذف شود",
    },
    operation: {
        title: "انجام عملیات",
        text: "آیا از :item اطمینان دارید؟",
        icon: "warning",
        confirmButtonText: "بله، انجام شود",
    },
};

document.addEventListener("submit", handleConfirm, true);
document.addEventListener("click", handleConfirm, true);

function handleConfirm(e) {
    let element = null;

    // اگر رویداد submit است → فقط فرم
    if (e.type === "submit") {
        element = e.target;
        if (!element.matches("[data-confirm]")) return;
    }

    // اگر رویداد click است → فقط لینک
    if (e.type === "click") {
        element = e.target.closest("a[data-confirm]");
        if (!element) return;
    }

    const isForm = element.tagName === "FORM";
    const isLink = element.tagName === "A";

    if (!isForm && !isLink) return;
    if (element.dataset.confirmed === "true") return;

    e.preventDefault();

    const type = element.dataset.confirm;
    const baseConfig = window.confirmConfig[type];
    if (!baseConfig) return;

    const item = element.dataset.confirmItem || "";
    let finalText = baseConfig.text.replace(":item", item);

    Swal.fire({
        title: baseConfig.title,
        text: finalText,
        icon: baseConfig.icon,
        confirmButtonText: baseConfig.confirmButtonText,
        cancelButtonText: "انصراف",
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            element.dataset.confirmed = "true";

            if (isForm) element.submit();
            if (isLink) window.location.href = element.href;
        }
    });
}

// function handleConfirm(e) {
//     const element = e.target.closest("[data-confirm]");
//     if (!element) return;

//     const isForm = element.tagName === "FORM";
//     const isLink = element.tagName === "A";

//     if (!isForm && !isLink) return;
//     if (element.dataset.confirmed === "true") return;

//     e.preventDefault();

//     const type = element.dataset.confirm;
//     const baseConfig = window.confirmConfig[type];
//     if (!baseConfig) return;

//     const item = element.dataset.confirmItem || "";

//     // جایگزینی placeholder
//     let finalText = baseConfig.text.replace(":item", item);

//     Swal.fire({
//         title: baseConfig.title,
//         text: finalText,
//         icon: baseConfig.icon,
//         confirmButtonText: baseConfig.confirmButtonText,
//         cancelButtonText: "انصراف",
//         showCancelButton: true,
//     }).then((result) => {
//         if (result.isConfirmed) {
//             element.dataset.confirmed = "true";

//             if (isForm) element.submit();
//             if (isLink) window.location.href = element.href;
//         }
//     });
// }
