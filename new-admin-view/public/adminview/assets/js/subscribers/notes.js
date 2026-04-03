// var NotesController = [
//     '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
//     function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
//         var self = this;
//         var editorInstance = null;

//         this.info = {};
//         this.note = {};
//         scope.errors = {};
//         requestFactory.getToaster();
//         requestFactory.setThisArgument(this);

//         this.defineProperties = function (data) {
//             this.info = data.info;
//             requestFactory.toggleLoader();
//         };

//         this.fetchInfo = function () {
//             requestFactory.get(
//                 requestFactory.getUrl('subscriber/note/info'),
//                 this.defineProperties,
//                 function (response) {
//                     rootScope.redirectUnauthenticated(response);
//                 }
//             );
//         };
//         this.fetchInfo();

//         // ==============================***********************************==============================
//         // ==============================***********************************==============================

//         // open form code
//         this.openNoteForm = function () {
//             this.isViewOnly = false;          // enable editing
//             this.note = {                     // clear the form model
//                 id: null,
//                 description: '',
//                 note_type: '',
//                 issue_with: '',
//                 subject: '',
//                 sub_note_type: ''
//             };

//             scope.gridSideFormOpen();

//             scope.$evalAsync(() => {
//                 setTimeout(() => {
//                     var rteEl = document.getElementById("div_editor1");
//                     if (rteEl && !window.editor1) {
//                         window.editor1 = new RichTextEditor(rteEl);
//                         editor1.attachEvent("change", function () {
//                             document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
//                         });
//                     }

//                     if (window.editor1) {
//                         window.editor1.setHTMLCode('');
//                         editor1.setReadOnly(false);
//                     }
//                 }, 100);
//             });

//             // Also reset the note in Angular scope
//             const homeElement = document.getElementById("flipFlop");
//             if (homeElement) {
//                 const localScope = angular.element(homeElement).scope();
//                 if (localScope && localScope.snCtrl) {
//                     const updateModel = () => {
//                         localScope.snCtrl.note = angular.copy(this.note);
//                     };
//                     if (!localScope.$$phase) {
//                         localScope.$apply(updateModel);
//                     } else {
//                         updateModel();
//                     }
//                 }
//             }
//         };


//         // edit note code
//         this.fetchnote = function (fetchnote) {
//             var self = this;
//             self.isViewOnly = false; // allow editing
//             self.note = angular.copy(fetchnote); // clone note into local model

//             scope.gridSideFormOpen();

//             scope.$evalAsync(function () {
//                 setTimeout(function () {
//                     var rteEl = document.getElementById("div_editor1");
//                     if (rteEl && !window.editor1) {
//                         window.editor1 = new RichTextEditor(rteEl);
//                         editor1.attachEvent("change", function () {
//                             document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
//                         });
//                     }

//                     if (fetchnote && window.editor1) {
//                         window.editor1.setHTMLCode(fetchnote.description || '');
//                         // ensure editor is editable
//                         editor1.setReadOnly(false);
//                     }
//                 }, 100);
//             });

//             const homeElement = document.getElementById("flipFlop");
//             if (!homeElement) return;

//             const localScope = angular.element(homeElement).scope();

//             if (fetchnote && localScope && localScope.snCtrl) {
//                 const updateModel = () => {
//                     localScope.snCtrl.note = {
//                         id: fetchnote.id,
//                         description: fetchnote.description || '',
//                         note_type: fetchnote.note_type || '',
//                         issue_with: fetchnote.issue_with || '',
//                         subject: fetchnote.subject || '',
//                         sub_note_type: fetchnote.sub_note_type || ''
//                     };
//                 };

//                 if (!localScope.$$phase) {
//                     localScope.$apply(updateModel);
//                 } else {
//                     updateModel();
//                 }
//             } else {
//                 console.warn('No data found for this note or scope not available');
//             }

//             // Optional debug
//             // console.log(fetchnote);
//         };

//         // view note code
//         this.viewrecord = function (record) {
//             this.isViewOnly = true;
//             this.note = angular.copy(record);
//             scope.gridSideFormOpen();

//             scope.$evalAsync(function () {
//                 setTimeout(function () {
//                     var rteEl = document.getElementById("div_editor1");
//                     if (rteEl && !window.editor1) {
//                         window.editor1 = new RichTextEditor(rteEl);
//                         editor1.attachEvent("change", function () {
//                             document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
//                         });
//                     }

//                     if (record && window.editor1) {
//                         window.editor1.setHTMLCode(record.description || '');
//                         editor1.setReadOnly(true);
//                     }
//                 }, 100);
//             });

//             const homeElement = document.getElementById("flipFlop");
//             if (!homeElement) return;

//             const localScope = angular.element(homeElement).scope();
//             if (record) {
//                 if (localScope && localScope.snCtrl) {
//                     const updateModel = () => {
//                         localScope.snCtrl.note = {
//                             id: record.id,
//                             description: record.description || '',
//                             note_type: record.note_type || '',
//                             issue_with: record.issue_with || '',
//                             subject: record.subject || '',
//                             sub_note_type: record.sub_note_type || ''
//                         };
//                     };

//                     if (!localScope.$$phase) {
//                         localScope.$apply(updateModel);
//                     } else {
//                         updateModel();
//                     }
//                 }
//             } else {
//                 console.warn(`No data found for this note`);
//             }
//         }

//         // save note code
//         this.save = function ($event, $id) {
//             $event.preventDefault();

//             const urlParams = new URLSearchParams(window.location.search);
//             const subscriberId = urlParams.get('subscriber-id');
//             if (!subscriberId) {
//                 requestFactory.showError('Subscriber ID is missing.');
//                 return;
//             }

//             const payload = {
//                 subscriber_id: subscriberId,
//                 note_type: this.note.note_type,
//                 issue_with: this.note.issue_with,
//                 subject: this.note.ubject,
//                 sub_note_type: this.note.sub_note_type,
//                 description: window.editor1 ? window.editor1.getHTMLCode() : '',
//             }

//             if ($id) {
//                 requestFactory.post(requestFactory.getUrl('subscriber/note/edit/' + $id), payload, function (response) {
//                     scope.getRecords();
//                     requestFactory.setToaster(response.message);
//                     requestFactory.getToaster();
//                     this.closeNoteForm();
//                     setTimeout(() => {
//                         window.location.reload();
//                     }, 500);
//                 }, this.fillErrors);
//             } else {
//                 requestFactory.post(requestFactory.getUrl('subscriber/note/add'), payload, function (response) {
//                     scope.getRecords();
//                     requestFactory.setToaster(response.message);
//                     requestFactory.getToaster();
//                     this.closeNoteForm();
//                     setTimeout(() => {
//                         window.location.reload();
//                     }, 500);
//                 }, this.fillErrors);
//             }

//         }

//         this.closeNoteForm = function () {
//             scope.gridSideFormClose();
//         };

//         // ==============================***********************************==============================
//         // ==============================***********************************==============================

//         // optional: listen to pagination or other events
//         scope.$on('afterGetRecords', function (e, data) {
//             if (angular.isUndefined(scope.searchRecords.is_active)) {
//                 scope.searchRecords.is_active = 'all';
//             }
//         });
//     }
// ];

// window.gridControllers = {
//     NotesController: NotesController
// };



var NotesController = [
    '$scope', 'requestFactory', '$window', '$sce', '$timeout', '$compile', '$interval', '$rootScope',
    function (scope, requestFactory, $window, sce, $timeout, compile, $interval, rootScope) {
        var self = this;
        var editorInstance = null;

        this.info = {};
        this.note = {};
        scope.errors = {};
        requestFactory.getToaster();
        requestFactory.setThisArgument(this);

        this.defineProperties = function (data) {
            this.info = data.info;
            requestFactory.toggleLoader();
        };

        this.fetchInfo = function () {
            requestFactory.get(
                requestFactory.getUrl('subscriber/note/info'),
                this.defineProperties,
                function (response) {
                    rootScope.redirectUnauthenticated(response);
                }
            );
        };
        this.fetchInfo();

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        // open form code
        this.openNoteForm = function () {
            this.isViewOnly = false;          // enable editing
            this.note = {                     // clear the form model
                id: null,
                description: '',
                note_type: '',
                issue_with: '',
                subject: '',
                sub_note_type: ''
            };

            if (typeof scope.gridSideFormOpen === 'function') {
                scope.gridSideFormOpen();
            } else {
                console.warn('gridSideFormOpen is not defined');
            }

            scope.$evalAsync(() => {
                setTimeout(() => {
                    var rteEl = document.getElementById("div_editor1");
                    if (rteEl && !window.editor1) {
                        window.editor1 = new RichTextEditor(rteEl);
                        editor1.attachEvent("change", function () {
                            document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
                        });
                    }

                    if (window.editor1) {
                        window.editor1.setHTMLCode('');
                        editor1.setReadOnly(false);
                    }
                }, 100);
            });

            // Also reset the note in Angular scope
            const homeElement = document.getElementById("flipFlop");
            if (homeElement) {
                const localScope = angular.element(homeElement).scope();
                if (localScope && localScope.snCtrl) {
                    const updateModel = () => {
                        localScope.snCtrl.note = angular.copy(this.note);
                    };
                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            }
        };

        // edit note code
        this.fetchnote = function (fetchnote) {
            var self = this;
            self.isViewOnly = false; // allow editing
            self.note = angular.copy(fetchnote); // clone note into local model

            if (typeof scope.gridSideFormOpen === 'function') {
                scope.gridSideFormOpen();
            } else {
                console.warn('gridSideFormOpen is not defined');
            }

            scope.$evalAsync(function () {
                setTimeout(function () {
                    var rteEl = document.getElementById("div_editor1");
                    if (rteEl && !window.editor1) {
                        window.editor1 = new RichTextEditor(rteEl);
                        editor1.attachEvent("change", function () {
                            document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
                        });
                    }

                    if (fetchnote && window.editor1) {
                        window.editor1.setHTMLCode(fetchnote.description || '');
                        // ensure editor is editable
                        editor1.setReadOnly(false);
                    }
                }, 100);
            });

            const homeElement = document.getElementById("flipFlop");
            if (!homeElement) return;

            const localScope = angular.element(homeElement).scope();

            if (fetchnote && localScope && localScope.snCtrl) {
                const updateModel = () => {
                    localScope.snCtrl.note = {
                        id: fetchnote.id,
                        description: fetchnote.description || '',
                        note_type: fetchnote.note_type || '',
                        issue_with: fetchnote.issue_with || '',
                        subject: fetchnote.subject || '',
                        sub_note_type: fetchnote.sub_note_type || ''
                    };
                };

                if (!localScope.$$phase) {
                    localScope.$apply(updateModel);
                } else {
                    updateModel();
                }
            } else {
                console.warn('No data found for this note or scope not available');
            }
            // Optional debug
            // console.log(fetchnote);
        };

        // view note code
        this.viewrecord = function (record) {
            this.isViewOnly = true;
            this.note = angular.copy(record);
            if (typeof scope.gridSideFormOpen === 'function') {
                scope.gridSideFormOpen();
            } else {
                console.warn('gridSideFormOpen is not defined');
            }

            scope.$evalAsync(function () {
                setTimeout(function () {
                    var rteEl = document.getElementById("div_editor1");
                    if (rteEl && !window.editor1) {
                        window.editor1 = new RichTextEditor(rteEl);
                        editor1.attachEvent("change", function () {
                            document.getElementById("inp_htmlcode").value = editor1.getHTMLCode();
                        });
                    }

                    if (record && window.editor1) {
                        window.editor1.setHTMLCode(record.description || '');
                        editor1.setReadOnly(true);
                    }
                }, 100);
            });

            const homeElement = document.getElementById("flipFlop");
            if (!homeElement) return;

            const localScope = angular.element(homeElement).scope();
            if (record) {
                if (localScope && localScope.snCtrl) {
                    const updateModel = () => {
                        localScope.snCtrl.note = {
                            id: record.id,
                            description: record.description || '',
                            note_type: record.note_type || '',
                            issue_with: record.issue_with || '',
                            subject: record.subject || '',
                            sub_note_type: record.sub_note_type || ''
                        };
                    };

                    if (!localScope.$$phase) {
                        localScope.$apply(updateModel);
                    } else {
                        updateModel();
                    }
                }
            } else {
                console.warn(`No data found for this note`);
            }
        }

        // save note code
        // this.save = function ($event, $id) {
        //     $event.preventDefault();

        //     const urlParams = new URLSearchParams(window.location.search);
        //     const subscriberId = urlParams.get('subscriber-id');
        //     if (!subscriberId) {
        //         requestFactory.showError('Subscriber ID is missing.');
        //         return;
        //     }

        //     const payload = {
        //         subscriber_id: subscriberId,
        //         note_type: this.note.note_type,
        //         issue_with: this.note.issue_with,
        //         subject: this.note.ubject,
        //         sub_note_type: this.note.sub_note_type,
        //         description: window.editor1 ? window.editor1.getHTMLCode() : '',
        //     }

        //     if ($id) {
        //         requestFactory.post(requestFactory.getUrl('subscriber/note/edit/' + $id), payload, function (response) {
        //             scope.getRecords();
        //             requestFactory.setToaster(response.message);
        //             requestFactory.getToaster();
        //             this.closeNoteForm();
        //             setTimeout(() => {
        //                 window.location.reload();
        //             }, 500);
        //         }, this.fillErrors);
        //     } else {
        //         requestFactory.post(requestFactory.getUrl('subscriber/note/add'), payload, function (response) {
        //             scope.getRecords();
        //             requestFactory.setToaster(response.message);
        //             requestFactory.getToaster();
        //             this.closeNoteForm();
        //             setTimeout(() => {
        //                 window.location.reload();
        //             }, 500);
        //         }, this.fillErrors);
        //     }

        // }

        this.save = function ($event, $id) {
            $event.preventDefault();

            const self = this; // ✅ preserve controller context

            const urlParams = new URLSearchParams(window.location.search);
            const subscriberId = urlParams.get('subscriber-id');

            if (!subscriberId) {
                requestFactory.showError('Subscriber ID is missing.');
                return;
            }

            const payload = {
                subscriber_id: subscriberId,
                note_type: self.note.note_type,
                issue_with: self.note.issue_with,
                subject: self.note.subject, // ❗ FIXED TYPO (was ubject)
                sub_note_type: self.note.sub_note_type,
                description: window.editor1 ? window.editor1.getHTMLCode() : '',
            };

            const successCallback = function (response) {

                // ✅ Fix: call correctly if exists
                if (typeof scope.getRecords === 'function') {
                    scope.getRecords();
                }

                requestFactory.setToaster(response.message);
                requestFactory.getToaster();

                self.closeNoteForm(); // ✅ FIXED

                setTimeout(() => {
                    window.location.reload();
                }, 500);
            };

            const errorCallback = self.fillErrors;

            if ($id) {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/note/edit/' + $id),
                    payload,
                    successCallback,
                    errorCallback
                );
            } else {
                requestFactory.post(
                    requestFactory.getUrl('subscriber/note/add'),
                    payload,
                    successCallback,
                    errorCallback
                );
            }
        };

        // this.closeNoteForm = function () {
        //     scope.gridSideFormClose();
        // };

        this.closeNoteForm = function () {
            if (typeof scope.gridSideFormClose === 'function') {
                scope.gridSideFormClose();
            } else {
                console.warn('gridSideFormClose is not defined');
            }
        };

        // ==============================***********************************==============================
        // ==============================***********************************==============================

        // optional: listen to pagination or other events
        scope.$on('afterGetRecords', function (e, data) {
            if (!scope.searchRecords) {
                scope.searchRecords = {};
            }

            if (angular.isUndefined(scope.searchRecords.is_active)) {
                scope.searchRecords.is_active = 'all';
            }
        });
    }
];

window.gridControllers = {
    NotesController: NotesController
};
