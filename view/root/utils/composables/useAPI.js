import { computed } from "vue";
import { useStore } from "vuex";
import RequestUtility from "@utils/RequestUtility.js";
import { toCapitalCase, toSingular } from "@utils/helpers.js";
import { useToast } from "primevue/usetoast";

export function useAPI(dataTopic = "") {
  const store = useStore();
  const toast = useToast();
  const defaultToast = {
    severity: "info",
    summary: "API Request",
    detail: "API request completed.",
    life: 3000,
  };

  const showWarningToast = (warningMessage) => {
    if (!warningMessage) return;

    toast.add({
      ...defaultToast,
      severity: "warn",
      summary: "Warning",
      detail: warningMessage,
    });
  };

  const openPdfWindow = (pdfSrc, documentName, revokeUrl) => {
    const pdfWindow = window.open("");
    if (!pdfWindow) {
      if (revokeUrl) {
        revokeUrl();
      }
      throw new Error("Unable to open document window.");
    }

    pdfWindow.document.title = documentName;
    pdfWindow.document.write(
      `<iframe width='100%' height='100%' title='${documentName}' src='${pdfSrc}'></iframe>`,
    );

    if (revokeUrl) {
      pdfWindow.onbeforeunload = revokeUrl;
    }

    return pdfWindow;
  };

  const get = (params = {}, topic = dataTopic) => {
    const stateSetter = "set" + toCapitalCase(topic);
    const requestData = {
      type: "get",
      url: topic,
      params: params,
      success: (res) => {
        store.commit(stateSetter, res.data.data);
      },
      error: (res) => {
        console.error(`Failed to get ${topic}:`, res);
      },
    };

    RequestUtility.sendRequest(requestData);

    return computed(() => store.state[topic]);
  };

  const post = (
    formData,
    topic = dataTopic,
    refresh = true,
    successMessage = "",
    errorMessage = "",
  ) => {
    return new Promise((resolve, reject) => {
      RequestUtility.sendRequest({
        type: "post",
        data: formData, // converted to FormData object in sendRequest
        url: topic,
        success: (res) => {
          if (refresh) {
            get({ active: 1, trashed: 0 }, topic);
          }
          toast.add({
            ...defaultToast,
            severity: "success",
            summary: "Success",
            detail: successMessage,
          });
          showWarningToast(res.data?.meta?.warning);
          resolve(res.data);
        },
        error: (res) => {
          console.error(`Failed to post new ${topic}:`, res);
          toast.add({
            ...defaultToast,
            severity: "error",
            summary: "Error",
            detail: res.response ? res.response.data.data : errorMessage,
          });
          reject(res);
        },
      });
    });
  };

  const trash = (data, topic = dataTopic, refresh = true) => {
    return new Promise((resolve, reject) => {
      RequestUtility.sendRequest({
        type: "delete",
        data: data, // converted to FormData object in sendRequest
        url: topic,
        success: (res) => {
          if (refresh) {
            get({}, topic);
          }
          toast.add({
            severity: "info",
            summary: toCapitalCase(topic) + " Deleted",
            detail: `${res.data.data.name} has been deleted.`,
            life: 3000,
          });
          resolve(res.data);
        },
        error: (res) => {
          console.error(`Failed to delete ${topic}:`, res);
          reject(res);
        },
      });
    });
  };

  const patch = (
    data,
    topic,
    endpoint,
    successMessage,
    errorMessage,
    refresh = true,
  ) => {
    console.log("Data send through patch request: ", data);

    return new Promise((resolve, reject) => {
      RequestUtility.sendRequest({
        type: "patch",
        data: data,
        url: topic + "/" + endpoint,
        success: (res) => {
          if (refresh) {
            get({ active: 1, trashed: 0 }, topic);
          }
          toast.add({
            ...defaultToast,
            severity: "success",
            summary: toCapitalCase(topic) + successMessage,
            detail: `${toCapitalCase(toSingular(topic))} has been ${successMessage.toLowerCase()}.`,
          });
          resolve(res.data);
        },
        error: (res) => {
          console.error(
            `Failed to ${errorMessage.toLowerCase()} ${topic}:`,
            res,
          );
          toast.add({
            ...defaultToast,
            severity: "danger",
            summary: toCapitalCase(topic) + errorMessage,
            detail: `Error marking item as ${errorMessage.toLowerCase()}`,
          });
          reject(res);
        },
      });
    });
  };

  const update = (data, topic = dataTopic, refresh = true) => {
    return patch(data, topic, "", " Updated", "Updated", refresh);
  };

  const markInactive = (data, topic = dataTopic) => {
    return patch(data, topic, "inactive", " Archived", "Archived");
  };

  const markActive = (data, topic = dataTopic) => {
    return patch(data, topic, "active", " Unarchived", "Unarchived");
  };

  const restore = (data, topic = dataTopic) => {
    return patch(data, topic, "restore", " Restored", "Archived");
  };

  const print = (
    data,
    endpoint = dataTopic,
    documentName = "Document",
    options = {},
  ) => {
    return new Promise((resolve, reject) => {
      const useBlobResponse = options.responseType === "blob";
      RequestUtility.sendRequest({
        type: "post",
        data: data, // converted to FormData object in sendRequest
        url: endpoint,
        headers: {
          ...(options.headers || {}),
          ...(useBlobResponse ? { Accept: "application/pdf" } : {}),
        },
        responseType: options.responseType,
        success: (res) => {
          let revokeUrl = null;
          let pdfSrc;

          if (useBlobResponse) {
            const pdfBlob =
              res.data instanceof Blob
                ? res.data
                : new Blob([res.data], { type: "application/pdf" });
            pdfSrc = URL.createObjectURL(pdfBlob);
            revokeUrl = () => URL.revokeObjectURL(pdfSrc);
          } else {
            pdfSrc = `data:application/pdf;base64,${res.data.data.pdf}`;
          }

          openPdfWindow(pdfSrc, documentName, revokeUrl);
          toast.add({
            severity: "success",
            summary: "Success",
            detail: `${documentName} printed successfully.`,
            life: 3000,
          });
          resolve(useBlobResponse ? res : res.data);
        },
        error: (res) => {
          console.error(`Failed to print ${documentName}:`, res);
          toast.add({
            severity: "error",
            summary: "Error",
            detail: `Failed to print ${documentName}.`,
            life: 3000,
          });
          reject(res);
        },
      });
    });
  };

  return { get, post, trash, update, markInactive, markActive, restore, print };
}
