<template>
    <div>
        <div class="row">
            <div class="col-sm-12" v-show="cardInbox">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title text-primary">{{ $route.name }}</h3>
                        <div class="card-tools">
                            <div class="input-group">
                                <input type="text"
                                       class="form-control input-sm"
                                       placeholder="Search Mail"
                                       v-model="search_mail">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default" title="Search" @click="getInbox">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-default"
                                            title="Clear"
                                            @click="clearSearchMail">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mailbox-controls">

                            <button type="button" class="btn btn-default">
                                <input type="checkbox" @click="selectAll" v-model="allSelected">
                            </button>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button
                                    type="button"
                                    title="Refresh"
                                    class="btn btn-default"

                                    @click="refeshInbox">

                                    <i class="fas fa-sync"></i>
                                </button>

                                <button
                                    v-if="$route.name === 'Inbox' || $route.name === 'Starred'"
                                    type="button"
                                    title="Toggle show unread emails"
                                    class="btn btn-default"

                                    @click="toggleShowUnreadMessages()">

                                    <i :class="toggleShowUnreadEmails ? 'fas fa-toggle-on text-primary' : 'fas fa-toggle-off'"></i>
                                </button>

                                <button
                                    v-if="$route.name === 'Inbox' || $route.name === 'Starred'"
                                    :disabled="btnEnable"
                                    type="button"
                                    title="Mark as read"
                                    class="btn btn-default"

                                    @click="markAsReadSelectedEmails()">

                                    <i class="far fa-envelope-open"></i>
                                </button>

                                <button
                                    v-if="$route.name === 'Inbox' || $route.name === 'Starred'"
                                    :disabled="btnEnable"
                                    type="button"
                                    title="Mark as unread"
                                    class="btn btn-default"

                                    @click="markAsUnReadSelectedEmails()">

                                    <i class="far fa-envelope"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Starred"
                                    class="btn btn-default"
                                    :disabled="btnEnable || $route.name === 'Trash'"

                                    @click="submitStarredThread(null, null, 'all')">

                                    <i class="fa fa-fw fa-star"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Label"
                                    class="btn btn-default"
                                    data-toggle="modal"
                                    data-target="#modal-label-selection"
                                    :disabled="btnEnable">

                                    <i class="fa fa-fw fa-tag"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Trash"
                                    class="btn btn-default"
                                    :disabled="btnEnable || $route.name === 'Trash'"

                                    @click="deleteMessage(0,0,true)">

                                    <i class="fa fa-fw fa-trash"></i>
                                </button>

                                <button
                                    type="button"
                                    title="Retrieve Email"
                                    class="btn btn-default"
                                    v-if="$route.name === 'Trash'"

                                    @click="retrieveDeletedMessage">

                                    <i class="fa fa-fw fa-recycle"></i>
                                </button>
                            </div>

                            <div class="float-right">
                                {{ paginate.from + '-' + paginate.to + " / " + paginate.total }}
                                <div class="btn-group">
                                    <button
                                        type="button"
                                        class="btn btn-default btn-sm"
                                        :disabled="records.current_page == 1"

                                        @click="getInbox(page = paginate.prev)">

                                        <i class="fa fa-chevron-left"></i>
                                    </button>

                                    <button
                                        type="button"
                                        class="btn btn-default btn-sm"
                                        :disabled="records.next_page_url == null"

                                        @click="getInbox(page = paginate.next)">

                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <div class="p-5 text-center text-muted" v-show="loadingMessage">
                            <i class="fa fa-refresh fa-spin mr-3"></i> loading messages
                        </div>
                        <div class="table-responsive">
                            <table class="table table-condensed table-hover" style="table-layout: auto; width: 100%">
                                <tbody>
                                <tr v-show="paginate.total == 0">
                                    <td class="text-muted text-center">No {{ $route.name }} record</td>
                                </tr>

                                <tr
                                    v-for="(inbox, index) in records.data"
                                    :key="index"
                                    :class="{
                                        'text-muted': $route.name != 'Trash'
                                            ? inbox.thread
                                                ? !checkIsViewedForThreads(inbox.thread)
                                                : false
                                            : inbox.is_viewed == 1,
                                        'font-weight-bold': $route.name != 'Trash'
                                            ? inbox.thread
                                                ? checkIsViewedForThreads(inbox.thread)
                                                : false
                                            : inbox.is_viewed == 0,
                                        'active': viewContent.id == inbox.id,
                                    }">
                                    <td style="width:30px;">
                                        <input
                                            v-model="checkIds"
                                            :id="inbox.id"
                                            :value="inbox"
                                            type="checkbox"

                                            @change="checkSelected">
                                    </td>

                                    <td v-if="$route.name === 'Sent'">
                                        <span class="badge" :class="statusClass(inbox.status)">
                                            {{ statusLabel(inbox.status) }}
                                        </span>
                                    </td>

                                    <td @click="viewMessage(inbox, index, $route.name)">
                                        <i
                                            v-show="inbox.label_id != 0"
                                            :style="{'color': inbox.label_color}"
                                            class="fa fa-tag mr-3">
                                        </i>
                                        {{
                                            $route.name != 'Trash'
                                                ? inbox.thread
                                                    ? inbox.is_sent == 0
                                                        ? displayInboxNameAndThreadCount(inbox.thread)
                                                        : 'To: ' + checkEmail(inbox.received)
                                                    : inbox.is_sent == 1
                                                        ? 'To: ' + checkEmail(inbox.received)
                                                        : inbox.from_mail
                                                : inbox.from_mail
                                        }}

                                        <span
                                            v-if="inbox.thread && inbox.thread.length > 1 && $route.name != 'Trash'"
                                            class="badge badge-pill badge-secondary">

                                            {{ inbox.thread.length }}
                                        </span>
                                    </td>

                                    <td @click="viewMessage(inbox, index, $route.name)">{{ inbox.subject }}</td>

                                    <td style="width:30px;" class="text-right" v-if="$route.name !== 'Trash'">
                                        <i
                                            :class="{
                                                'fa': true,
                                                'fa-fw': true,
                                                'orange': true,
                                                'pointer': true,
                                                'fa-star': $route.name != 'Trash'
                                                    ? inbox.thread
                                                        ? checkIsStarredForThreads(inbox.thread)
                                                        : false
                                                    : inbox.is_starred == 1,
                                                'fa-star-o': $route.name != 'Trash'
                                                    ? inbox.thread
                                                        ? !checkIsStarredForThreads(inbox.thread)
                                                        : false
                                                    : inbox.is_starred == 0
                                            }"
                                            title="Starred"

                                            @click="submitStarredThread(inbox, index, 'single')">
                                        </i>
                                    </td>

                                    <td @click="viewMessage(inbox, index, $route.name)">
                                        <i
                                            v-show="
                                                $route.name != 'Trash'
                                                    ? inbox.thread
                                                        ? checkHaveAttachmentsForThreads(inbox.thread)
                                                        : false
                                                    : ((inbox.attachment != '' && inbox.attachment != '[]') || inbox.stored_attachments != null)
                                            "
                                            class="fa fa-fw fa-paperclip">
                                        </i>
                                    </td>

                                    <td @click="viewMessage(inbox, index, $route.name)" class="text-right">
                                        {{ inbox.clean_date }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12" v-show="cardReadMessage">
                <div class="card">
                    <div v-show="selectedMessage" class="text-center text-muted p-5 h-50">
                        No items Selected
                    </div>

                    <div v-show="MessageDisplay" class="box-header with-border">
                        <h3 class="box-title">
                            <button type="button"
                                    title="Back"
                                    class="btn btn-default btn-sm mr-3"
                                    @click="clearViewing()"><i class="fa fa-chevron-left"></i></button>
                            Read Mail
                        </h3>

                        <!-- <div class="box-tools pull-right">
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Previous"><i class="fa fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="Next"><i class="fa fa-chevron-right"></i></a>
                        </div> -->

                    </div>

                    <div v-show="MessageDisplay" class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ viewContent.subject }}</h3>
                            <h5 v-show="viewContent.is_sent == 0">From: {{ viewContent.from }}
                                <span class="mailbox-read-time pull-right">{{ viewContent.date }}</span></h5>
                            <h5 v-show="viewContent.is_sent == 1">To: {{ checkEmail(viewContent.received) }}
                                <span class="mailbox-read-time pull-right">{{ viewContent.date }}</span></h5>
                        </div>

                        <div class="mailbox-read-message">
                            <!--                            <textarea class="form-control message-content" readonly>{{ viewContent.strippedHtml }}</textarea>-->
                            <!--                            <div id="htmlDiv" v-html="viewContent.strippedHtml" style="white-space: pre-line"></div>-->
                            <div>
                                <iframe
                                    ref="iframe"
                                    width="100%"
                                    frameborder="0">

                                </iframe>
                            </div>
                        </div>
                    </div>

                    <!-- For Attachment -->
                    <div v-show="MessageDisplay && viewAttachmentChecker(viewContent)" class="box-footer">

                        <!-- attachments for inbound emails -->
                        <div v-if="viewContent.stored_attachments !== null">
                            <ul class="mailbox-attachments clearfix">
                                <li
                                    v-if="viewContent.is_sent == 0 && viewContent.stored_attachments.length != 0"
                                    v-for="(attach, index) in viewContent.stored_attachments"
                                    :key="index">
                                    <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                        <a
                                            v-if="viewContent.duration_date < 30"
                                            href="#"
                                            :title="attach.name"
                                            :download="attach.name"
                                            class="mailbox-attachment-name"
                                            :href="'/storage/'+attach.path">

                                            <i class="fa fa-paperclip"></i>
                                            {{ attach.name }}
                                        </a>

                                        <a v-else class="mailbox-attachment-name text-danger">
                                            Deleted
                                        </a>

                                        <span class="mailbox-attachment-size">{{ bytesToSize(attach.size) }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div v-else>
                            <ul class="mailbox-attachments clearfix">

                                <!-- for old attachments stored in mailgun -->
                                <li
                                    v-if="viewContent.is_sent == 0 && viewContent.attachment.length != 0"
                                    v-for="(attach, index) in viewContent.attachment"
                                    :key="index">
                                    <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                        <a
                                            href="#"
                                            :title="attach.name"
                                            :id="'link-download-href-'+index"
                                            class="mailbox-attachment-name">

                                            <i class="fa fa-paperclip"></i>
                                            {{ attach.name }}
                                        </a>

                                        <span class="mailbox-attachment-size">{{ bytesToSize(attach.size) }}</span>
                                    </div>
                                </li>

                                <!-- attachments for outbound emails -->
                                <li v-if="viewContent.is_sent == 1 && !Array.isArray(viewContent.attachment)">
                                    <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                        <a
                                            target="_blank"
                                            class="mailbox-attachment-name"
                                            :download="viewContent.attachment.display_name"
                                            :href="viewContent.attachment.url">

                                            <i class="fa fa-paperclip"></i>
                                            {{ viewContent.attachment.display_name }}
                                        </a>

                                        <span class="mailbox-attachment-size">{{
                                                bytesToSize(viewContent.attachment.size)
                                            }}</span>
                                    </div>
                                </li>

                                <li v-if="viewContent.is_sent == 1 && Array.isArray(viewContent.attachment)"
                                    v-for="att in viewContent.attachment">
                                    <div class="mailbox-attachment-info mailbox-attachment-info-custom">
                                        <a
                                            target="_blank"
                                            class="mailbox-attachment-name"
                                            :download="att.display_name"
                                            :href="att.url">

                                            <i class="fa fa-paperclip"></i>
                                            {{ att.display_name }}
                                        </a>

                                        <span class="mailbox-attachment-size">{{ bytesToSize(att.size) }}</span>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>

                    <div v-show="MessageDisplay" class="card-footer">
                        <div class="float-right">
                            <button v-if="$route.name === 'Inbox' || $route.name === 'Sent' || $route.name === 'Starred'"
                                    type="button"
                                    class="btn btn-default"

                                    @click="doReply($route.name)">
                                <i class="fa fa-reply"></i>
                                {{ viewContent.is_sent === 1 ? 'Follow up' : 'Reply' }}
                            </button>
                        </div>
                        <button type="button"
                                class="btn btn-default"
                                v-show="viewContent.deleted_at == null"
                                @click="deleteMessage(viewContent.id, viewContent.index, false)">
                            <i class="fa fa-trash-o"></i> Delete
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-sm-12" v-show="cardReadMessageThread">
                <div class="card">
                    <!-- email header -->
                    <div v-show="MessageDisplay" class="box-header with-border">
                        <h3 class="box-title">
                            <button
                                type="button"
                                title="Back"
                                class="btn btn-default btn-sm mr-3"

                                @click="clearViewing()">
                                <i class="fa fa-chevron-left"></i>
                            </button>

                            Read Mail
                        </h3>
                    </div>

                    <!-- email subject -->
                    <div v-show="MessageDisplay" class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ viewContentThread.inbox.subject }}</h3>
                        </div>
                    </div>

                    <!-- email contents -->
                    <div v-show="MessageDisplay" v-for="email in viewContentThread.threads" class="box-body no-padding">
                        <!-- email recipients/sender and date -->
                        <div class="mailbox-read-info">
                            <h6 class="font-weight-bold mb-0">
                                {{ email.from_mail }}

                                <span class="float-right">
                                    <span v-if="viewAttachmentChecker(email)" class="mr-2" style="font-size: 13px !important;">
                                        <i class="fa fa-paperclip"></i>
                                    </span>

                                    <!-- toggle quoted messages -->
                                    <span
                                        class="mr-2"
                                        title="Toggle quoted messages"
                                        style="cursor:pointer; font-size: 13px !important;">

                                        <i
                                            :id="'toggle' + email.id"
                                            class="fa fa-angle-double-down"

                                            @click="toggleQuotedMessage('iframe' + email.id)"></i>
                                    </span>

                                    <!-- reply -->
                                    <span
                                        v-if="($route.name === 'Inbox' || $route.name === 'Sent' || $route.name === 'Starred') && email.is_sent === 0"
                                        class="mr-2"
                                        title="Reply"
                                        style="cursor: pointer"

                                        @click="doReply($route.name, email)">

                                        <i class="fa fa-reply"></i>
                                    </span>

                                    <!-- forward -->
                                    <span
                                        v-if="$route.name === 'Inbox' || $route.name === 'Sent'"
                                        class="mr-2"
                                        title="Forward message"
                                        style="cursor: pointer"

                                        @click="doForward($route.name, email)">

                                        <i class="fa fa-share"></i>
                                    </span>

                                    <span class="mailbox-read-time">
                                        {{ email.full_clean_date }}
                                    </span>
                                </span>
                            </h6>

                            <small class="text-muted">
                                To: {{ user.work_mail === email.received ? 'Me' : checkEmail(email.received) }}
                            </small>
                        </div>

                        <!-- email body -->
                        <div class="mailbox-read-message">
                            <div>
                                <iframe
                                    :id="'iframe' + email.id"
                                    :ref="'iframe' + email.id"
                                    width="100%"
                                    frameborder="0">

                                </iframe>
                            </div>
                        </div>

                        <!-- email attachments -->
                        <div v-show="MessageDisplay && viewAttachmentChecker(email)" class="box-footer">

                            <!-- attachments for inbound emails -->
                            <div v-if="email.stored_attachments !== null">
                                <ul class="mailbox-attachments clearfix">
                                    <li
                                        v-if="email.is_sent === 0 && email.stored_attachments.length !== 0"
                                        v-for="(attach, index) in email.stored_attachments"
                                        :key="index">
                                        <div class="mailbox-attachment-info mailbox-attachment-info-custom card">
                                            <a
                                                v-if="email.duration_date < 30"
                                                href="#"
                                                class="mailbox-attachment-name"
                                                :title="attach.name"
                                                :download="attach.name"
                                                :href="'/storage/'+attach.path">

                                                <i class="fa fa-paperclip"></i>
                                                {{ attach.name }}
                                            </a>

                                            <a v-else class="mailbox-attachment-name text-danger">
                                                Deleted
                                            </a>

                                            <span class="mailbox-attachment-size">
                                                {{ bytesToSize(attach.size) }}
                                            </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div v-else>
                                <ul class="mailbox-attachments clearfix">

                                    <!-- for old attachments stored in mailgun -->
                                    <li
                                        v-if="email.is_sent === 0 && email.attachment.length !== 0"
                                        v-for="(attach, index) in email.attachment"
                                        :key="index">
                                        <div class="mailbox-attachment-info mailbox-attachment-info-custom card">
                                            <a
                                                href="#"
                                                class="mailbox-attachment-name"
                                                :title="attach.name"
                                                :id="'link-download-href-' + index + email.id">

                                                <i class="fa fa-paperclip"></i>
                                                {{ attach.name }}
                                            </a>

                                            <span class="mailbox-attachment-size">
                                            {{ bytesToSize(attach.size) }}
                                        </span>
                                        </div>
                                    </li>

                                    <!-- attachments for outbound emails -->
                                    <li v-if="email.is_sent === 1 && !Array.isArray(email.attachment)">
                                        <div
                                            v-if="!Array.isArray(email.attachment)"
                                            class="mailbox-attachment-info mailbox-attachment-info-custom card">
                                            <a
                                                target="_blank"
                                                class="mailbox-attachment-name"
                                                :download="email.attachment.display_name"
                                                :href="email.attachment.url">

                                                <i class="fa fa-paperclip"></i>
                                                {{ email.attachment.display_name }}
                                            </a>

                                            <span class="mailbox-attachment-size">
                                            {{ bytesToSize(email.attachment.size) }}
                                        </span>
                                        </div>
                                    </li>

                                    <li
                                        v-if="email.is_sent === 1 && Array.isArray(email.attachment)"
                                        v-for="att in email.attachment">
                                        <div class="mailbox-attachment-info mailbox-attachment-info-custom card">
                                            <a
                                                target="_blank"
                                                class="mailbox-attachment-name"
                                                :download="att.display_name"
                                                :href="att.url">

                                                <i class="fa fa-paperclip"></i>
                                                {{ att.display_name }}
                                            </a>

                                            <span class="mailbox-attachment-size">
                                            {{ bytesToSize(att.size) }}
                                        </span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <hr>
                    </div>

                    <!-- email footer buttons -->
                    <div v-show="MessageDisplay" class="card-footer">
                        <div class="float-right">
                            <button
                                v-if="$route.name === 'Inbox' || $route.name === 'Sent' || $route.name === 'Starred'"
                                type="button"
                                class="btn btn-default"

                                @click="doReply($route.name)">

                                <i class="fa fa-reply"></i>
                                {{ viewContentThread.inbox.is_sent === 1 ? 'Follow up' : 'Reply' }}
                            </button>
                        </div>

                        <button
                            type="button"
                            class="btn btn-default"

                            @click="deleteMessageThread(viewContentThread.threads, 'single')">

                            <i class="fa fa-trash-o"></i>
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Label -->
        <div id="modal-label-selection" class="modal fade">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Label</h4>
                    </div>
                    <div class="modal-body relative">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Label Name</label>
                                    <select class="form-control" v-model="updateModel.label_id">
                                        <option value=""></option>
                                        <option v-for="(label, index) in $parent._data.listLabel"
                                                v-bind:value="label.id"
                                                :key="index">
                                            {{ label.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="submitLabelThread">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Label -->

        <!-- Balloon wrapper -->
        <div class="bln-container">

            <!-- Compose Email Balloon -->
            <Balloon ref="emailBalloon" :title="'Compose Message'">

                <!-- custom header buttons -->
                <template v-slot:header-buttons>
                    <i class="fa fa-times" @click="modalCloser('Send')"></i>
                </template>

                <!-- note -->
                <div class="accordion" id="noteAccordion">
                    <div class="card">
                        <div
                            id="headingOne"
                            style="cursor: pointer;"
                            class="card-header p-2 font-italic text-secondary"
                            data-toggle="collapse"
                            data-target="#noteCollapse"
                            aria-expanded="true"
                            aria-controls="noteCollapse">

                            <i class="fa fa-exclamation-circle"></i> Note
                        </div>

                        <div id="noteCollapse" class="collapse" aria-labelledby="headingOne" data-parent="#noteAccordion">
                            <div class="card-body p-2 text-sm text-secondary">
                                <ul>
                                    <li>
                                        You can send an email to multiple recipients
                                        <strong>'contact01|contact02|contact03' or 'contact01,contact02,contact03'</strong>
                                    </li>
                                    <li>For bulk sending, <strong>only 10 recipients are allowed per email</strong></li>
                                    <li>
                                        You can edit the tags by clicking it and pressing
                                        <strong>enter key</strong>
                                        afterwards
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- form -->
                <form class="row" action="" style="font-size: .875rem !important;">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withBccCompose"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="toggleBcc('Send')">
                                        With Bcc
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withTemplateCompose"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="toggleTemplate('Send')">
                                        With Template
                                    </label>
                                </div>
                            </div>
                        </div>
                        <hr>
                    </div>

                    <div class="col-md-12" v-show="withTemplateCompose">
                        <div class="row">
                            <div class="col-md-4">
                                <div>
                                    <select
                                        v-model="countryMailIdCompose"
                                        class="form-control form-control-sm pull-right"
                                        :class="countryMailIdCompose === '' ? 'selected-placeholder' : ''"

                                        @change="getTemplateList('Send')">

                                        <option value="">Select Language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div>
                                        <select
                                            v-model="mailInfoCompose"
                                            class="form-control form-control-sm pull-right"
                                            :class="Object.keys(mailInfoCompose).length === 0 ? 'selected-placeholder' : ''"

                                            @change="getTemplate('send')">

                                            <option v-bind:value="{}">Select Template</option>
                                            <option v-for="option in listMailTemplateCompose.data" v-bind:value="option.id">
                                                {{ option.mail_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 py-0 my-0">
                        <div class="form-group">

                            <small
                                v-if="emailContent.email.length"
                                class="text-primary small"
                                style="cursor: pointer"

                                @click="emailContent.email = []">
                                Remove all emails
                            </small>

                            <vue-tags-input
                                v-model="tag"
                                placeholder="Recipients"
                                :max-tags="10"
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="emailContent.email"

                                @tags-changed="newTags => emailContent.email = newTags"
                            />

                            <span
                                v-if="messageForms.errors.email"
                                v-for="err in messageForms.errors.email"
                                class="text-danger">
                                {{ err }}
                            </span>

                            <span v-if="checkEmailValidationError(messageForms.errors)" class="text-danger">
                                The email field must contain valid emails.
                            </span>

                        </div>
                    </div>

                    <div class="col-md-12" v-show="withBccCompose">
                        <div class="form-group">
                            <input
                                v-model="emailContent.cc"
                                type="text"
                                placeholder="Bcc"
                                class="form-control form-control-sm"
                                required="required">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.title}"
                            class="form-group">
                            <input
                                v-model="emailContent.title"
                                type="text"
                                required="required"
                                placeholder="Subject"
                                class="form-control form-control-sm">

                            <span
                                v-if="messageForms.errors.title"
                                v-for="err in messageForms.errors.title"
                                class="text-danger">{{ err }}
                        </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.content}"
                            class="form-group">

                            <tiny-editor
                                v-model="emailContent.content"
                                ref="composeEditor"
                                editor-id="composeEditor">

                            </tiny-editor>

                            <span
                                v-if="messageForms.errors.content"
                                v-for="err in messageForms.errors.content"
                                class="text-danger">
                                {{ err }}
                            </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 col-xl-12 col-12">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    :disabled="sendBtn"

                                    @click="sendEmail('compose')">
                                    <i class="fa fa-paper-plane"></i> Send
                                </button>

                                <button
                                    type="button"
                                    title="Attach files"
                                    class="btn btn-primary btn-sm float-right"

                                    @click="$refs.file_send.click()">

                                    <i class="fa fa-paperclip"></i>
                                    {{
                                        attached_files.compose === 0
                                            ? 'Attach files'
                                            : attached_files.compose + ' file(s) attached'
                                    }}
                                </button>
                            </div>

                            <div class="col-md-6 col-xl-6 col-12">
                                <div class="form-group">
                                    <input
                                        multiple
                                        type="file"
                                        id="file_send"
                                        ref="file_send"
                                        class="form-control form-control-sm d-none"

                                        @change="getAttachments('compose')">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </Balloon>

            <!-- Reply Email Balloon -->
            <Balloon ref="emailReplyBalloon" :title="replyContent.title">

                <!-- custom header buttons -->
                <template v-slot:header-buttons>
                    <i class="fa fa-times" @click="modalCloser('Reply')"></i>
                </template>

                <!-- form -->
                <form class="row" action="" style="font-size: .875rem !important;">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withBccReply"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="toggleBcc('Reply')">
                                        With Bcc
                                    </label>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withTemplateReply"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="toggleTemplate('Reply')">
                                        With Template
                                    </label>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" v-show="withTemplateReply">
                        <div class="row">
                            <div class="col-md-4">
                                <div>
                                    <select
                                        v-model="countryMailIdReply"
                                        class="form-control form-control-sm pull-right"
                                        :class="countryMailIdReply === '' ? 'selected-placeholder' : ''"

                                        @change="getTemplateList('Reply')">

                                        <option value="">Select Language</option>
                                        <option v-for="option in listLanguages.data" v-bind:value="option.id">
                                            {{ option.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <div>
                                        <select
                                            v-model="mailInfoReply"
                                            class="form-control form-control-sm pull-right"
                                            :class="Object.keys(mailInfoReply).length === 0 ? 'selected-placeholder' : ''"

                                            @change="getTemplate('reply')">

                                            <option v-bind:value="{}">Select Template</option>
                                            <option v-for="option in listMailTemplateReply.data"
                                                    v-bind:value="option.id">
                                                {{ option.mail_name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 py-0 my-0">
                        <div class="form-group">
                            <vue-tags-input
                                v-model="tag"
                                ref="replyTag"
                                placeholder=""
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="replyContent.email"
                                :disabled="viewContent.is_sent !== 1"

                                @tags-changed="newTags => replyContent.email = newTags"
                            />

                            <span
                                v-if="messageForms.errors.email"
                                v-for="err in messageForms.errors.email"
                                class="text-danger">

                            {{ err }}
                        </span>
                        </div>
                    </div>

                    <div class="col-md-12" v-show="withBccReply">
                        <div class="form-group">
                            <input
                                v-model="replyContent.cc"
                                type="text"
                                placeholder="Bcc"
                                class="form-control form-control-sm"
                                required="required">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.content}"
                            class="form-group">

                            <tiny-editor
                                v-model="replyContent.content"
                                ref="replyEditor"
                                editor-id="replyEditor">

                            </tiny-editor>

                            <span
                                v-if="messageForms.errors.content"
                                v-for="err in messageForms.errors.content"
                                class="text-danger">

                            {{ err }}
                        </span>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 col-xl-12 col-12">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    :disabled="sendBtn"

                                    @click="sendEmail('reply')">
                                    <i class="fa fa-reply"></i> Reply
                                </button>

                                <button
                                    type="button"
                                    title="Attach files"
                                    class="btn btn-primary btn-sm float-right"

                                    @click="$refs.file_reply.click()">

                                    <i class="fa fa-paperclip"></i>
                                    {{
                                        attached_files.reply === 0
                                            ? 'Attach files'
                                            : attached_files.reply + ' file(s) attached'
                                    }}
                                </button>
                            </div>

                            <div class="col-md-6 col-xl-6 col-12">
                                <div class="form-group">
                                    <input
                                        multiple
                                        type="file"
                                        id="file_reply"
                                        ref="file_reply"
                                        class="form-control form-control-sm d-none"

                                        @change="getAttachments('reply')">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </Balloon>

            <!-- Forward Email Balloon -->
            <Balloon ref="emailForwardBalloon" :title="forwardContent.title">

                <!-- custom header buttons -->
                <template v-slot:header-buttons>
                    <i class="fa fa-times" @click="modalCloser('Forward')"></i>
                </template>

                <!-- form -->
                <form class="row" action="" style="font-size: .875rem !important;">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input
                                            v-model="withBccForward"
                                            type="checkbox"
                                            class="form-check-input"

                                            @click="toggleBcc('Forward')">
                                        With Bcc
                                    </label>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 py-0 my-0">
                        <div class="form-group">
                            <small
                                v-if="forwardContent.email.length"
                                class="text-primary small"
                                style="cursor: pointer"

                                @click="forwardContent.email = []">
                                Remove all emails
                            </small>

                            <vue-tags-input
                                v-model="tag"
                                placeholder="Recipients"
                                :max-tags="10"
                                :allow-edit-tags="true"
                                :separators="separators"
                                :tags="forwardContent.email"

                                @tags-changed="newTags => forwardContent.email = newTags"
                            />

                            <span
                                v-if="messageForms.errors.email"
                                v-for="err in messageForms.errors.email"
                                class="text-danger">
                            {{ err }}
                        </span>

                            <span v-if="checkEmailValidationError(messageForms.errors)" class="text-danger">
                            The email field must contain valid emails.
                        </span>

                        </div>
                    </div>

                    <div class="col-md-12" v-show="withBccForward">
                        <div class="form-group">
                            <input
                                v-model="forwardContent.cc"
                                type="text"
                                placeholder="Bcc"
                                class="form-control form-control-sm"
                                required="required">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div
                            :class="{'form-group': true, 'has-error': messageForms.errors.content}"
                            class="form-group">

                            <tiny-editor
                                v-model="forwardContent.content"
                                ref="forwardEditor"
                                editor-id="forwardEditor">

                            </tiny-editor>

                            <span
                                v-if="messageForms.errors.content"
                                v-for="err in messageForms.errors.content"
                                class="text-danger">

                            {{ err }}
                        </span>
                        </div>
                    </div>

                    <div v-if="forwardContent.forwardAttachments" class="col-md-12">
                        <div class="row">
                            <div v-for="attachment in forwardContent.forwardAttachments" class="col-md-3">
                                <div class="card">
                                    <div
                                        class="card-body p-1"
                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">

                                    <span
                                        class="text-primary font-weight-bold"
                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis">

                                        {{ forwardContent.is_sent === 1 ? attachment.display_name : attachment.name }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12 col-xl-12 col-12">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm"
                                    :disabled="sendBtn"

                                    @click="sendEmail('forward')">
                                    <i class="fa fa-share"></i> Forward
                                </button>

                                <button
                                    type="button"
                                    title="Attach files"
                                    class="btn btn-primary btn-sm float-right"

                                    @click="$refs.file_forward.click()">

                                    <i class="fa fa-paperclip"></i>
                                    {{
                                        attached_files.forward === 0
                                            ? 'Attach files'
                                            : attached_files.forward + ' file(s) attached'
                                    }}
                                </button>
                            </div>

                            <div class="col-md-6 col-xl-6 col-12">
                                <div class="form-group">
                                    <input
                                        multiple
                                        type="file"
                                        id="file_forward"
                                        ref="file_forward"
                                        class="form-control form-control-sm d-none"

                                        @change="getAttachments('forward')">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </Balloon>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {mapActions, mapState} from 'vuex';
import {createTag, createTags} from '@johmun/vue-tags-input';
import TinyEditor from "@/components/editor/TinyEditor";
import Balloon from '@/components/windows/Balloon';

export default {
    name : 'AppInbox',
    components : {TinyEditor, Balloon},
    data() {
        return {
            search_mail : '',
            emailContent : {
                cc : '',
                email : [],
                title : '',
                content : ''
            },
            replyContent : {
                cc : '',
                email : [],
                title : '',
                content : ''
            },
            forwardContent : {
                cc : '',
                email : [],
                title : '',
                is_sent : 0,
                content : '',
                forwardAttachments: [],
            },
            viewContent : {
                received : '',
                is_sent : '',
                from_mail : '',
                index : '',
                id : '',
                date : '',
                from : '',
                subject : '',
                strippedHtml : '',
                deleted_at : '',
                attachment : {
                    url : '',
                    size : '',
                    name : '',
                },
            },
            viewContentThread : {
                inbox : {},
                threads : [],
            },
            records : [],
            loadingMessage : false,
            selectedMessage : true,
            MessageDisplay : false,
            checkIds : [],
            btnEnable : true,
            inboxCount : 0,
            updateModel : {
                label_id : '',
            },
            allSelected : false,
            sendBtn : false,
            paginate : {
                next : 0,
                prev : 0,
                from : 0,
                to : 0,
                total : 0,
            },
            cardInbox : true,
            cardReadMessage : false,
            cardReadMessageThread : false,

            // for tag input component
            tag : '',
            separators : [
                ';',
                ',',
                '|',
                ' '
            ],

            // for attachments
            attached_files: {
                reply: 0,
                compose: 0,
                forward: 0
            },

            // bcc and template

            withBccCompose : false,
            withBccReply : false,
            withBccForward : false,

            withTemplateCompose : false,
            withTemplateReply : false,

            mailInfoCompose : {},
            countryMailIdCompose : '',

            mailInfoReply : {},
            countryMailIdReply : '',

            listMailTemplateCompose: {
                data: [],
                total: 0
            },
            listMailTemplateReply: {
                data: [],
                total: 0
            },

            toggleShowUnreadEmails: false
        }
    },

    created() {
        this.getListCountries();
        this.$root.$refs.AppInbox = this;

        let language = this.listLanguages.data;
        if (language.length === 0) {
            this.getListLanguages();
        }
    },

    computed : {
        ...mapState({
            user : state => state.storeAuth.currentUser,
            listCountryAll : state => state.storePublisher.listCountryAll,
            listMailTemplate : state => state.storeExtDomain.listMailTemplate,
            messageForms : state => state.storeMailgun.messageForms,
            listLanguages : state => state.storePublisher.listLanguages,
        })
    },

    watch : {
        $route(to, from) {
            this.getInbox();
            this.clearCheckIds();
            this.clearViewing();
            this.allSelected = false
        }
    },

    mounted() {
        this.getInbox();
    },

    methods : {
        ...mapActions({
            updateUnreadEmailsCount : "updateUnreadEmailsCount",
        }),

        modalCloser(mode) {
            if (mode === 'Send') {

                if (this.emailContent.content
                    || this.emailContent.email.length !== 0
                    || this.emailContent.title
                    || this.emailContent.cc) {

                    swal.fire({
                        title : "Save as Draft?",
                        text : "Save email contents as draft?",
                        icon : "question",
                        showCloseButton: true,
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        cancelButtonColor: 'red',
                        confirmButtonColor: 'green',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {

                            // save message content as draft
                            this.emailContent.mode = mode;

                            this.saveMessageAsDraft(this.emailContent);

                            this.closeModal(mode)
                            this.clearModel(mode)

                        } else if (result.dismiss == 'cancel') {

                            // remove all images inserted on editor
                            this.$refs.composeEditor.deleteImages('All');

                            this.closeModal(mode)
                            this.clearModel(mode)

                        }
                    });

                } else {
                    this.$refs.composeEditor.deleteImages('All');
                    this.clearModel(mode)
                    this.closeModal(mode)
                }

            } else if (mode === 'Forward') {

                if (this.forwardContent.content
                    || this.forwardContent.email.length !== 0
                    || this.forwardContent.title
                    || this.forwardContent.cc) {

                    swal.fire({
                        title : "Save as Draft?",
                        text : "Save email contents as draft?",
                        icon : "question",
                        showCloseButton: true,
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        cancelButtonColor: 'red',
                        confirmButtonColor: 'green',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {

                            // save message content as draft
                            this.forwardContent.mode = mode;

                            this.saveMessageAsDraft(this.forwardContent);

                            this.closeModal(mode)
                            this.clearModel(mode)

                        } else if (result.dismiss == 'cancel') {
                            this.closeModal(mode)
                            this.clearModel(mode)
                        }
                    });

                } else {
                    this.closeModal(mode)
                    this.clearModel(mode)
                }

            } else {
                if (this.replyContent.content
                    || this.replyContent.cc) {

                    swal.fire({
                        title : "Save as Draft?",
                        text : "Save email contents as draft?",
                        icon : "question",
                        showCloseButton: true,
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: 'No',
                        confirmButtonText: 'Yes',
                        cancelButtonColor: 'red',
                        confirmButtonColor: 'green',
                    })
                    .then((result) => {
                        if (result.isConfirmed) {

                            // save message content as draft
                            this.replyContent.mode = mode;

                            this.saveMessageAsDraft(this.replyContent);

                            this.closeModal(mode)
                            this.clearModel(mode)

                        } else if (result.dismiss == 'cancel') {
                            // remove all images inserted on editor
                            this.$refs.replyEditor.deleteImages('All');

                            this.closeModal(mode)
                            this.clearModel(mode)
                        }
                    });

                } else {
                    this.$refs.replyEditor.deleteImages('All');
                    this.clearModel(mode)
                    this.closeModal(mode)
                }
            }
        },

        closeModal(mode) {
            if (mode === 'Send' || mode === 'compose') {
                this.$refs.emailBalloon.close()
            } else if (mode === 'Forward' || mode === 'forward') {
                this.$refs.emailForwardBalloon.close()
            } else if (mode === 'Reply' || mode === 'reply') {
                this.$refs.emailReplyBalloon.close()
            }
        },

        clearModel(mode) {
            if (mode === 'Send' || mode === 'compose') {

                this.emailContent = {
                    cc : '',
                    email : [],
                    title : '',
                    content : ''
                }

                this.attached_files.compose = 0

            } else if (mode === 'Forward' || mode === 'forward') {

                this.forwardContent = {
                    cc : '',
                    email : [],
                    title : '',
                    is_sent : 0,
                    content : '',
                    forwardAttachments: [],
                }

                this.attached_files.forward = 0

            } else if (mode === 'Reply' || mode === 'reply') {

                this.replyContent = {
                    cc : '',
                    email : [],
                    title : '',
                    content : ''
                }

                this.attached_files.reply = 0

            }
        },

        checkIsViewedForThreads(thread) {
            return thread.some(el => (el.is_sent === 0 && el.is_viewed === 0));
        },

        checkIsStarredForThreads(thread) {
            return thread.some(el => el.is_starred === 1)
        },

        checkHaveAttachmentsForThreads(thread) {
            return thread.some(el => (el.attachment !== '' && el.attachment !== '[]') || el.stored_attachments !== null)
        },

        displayInboxNameAndThreadCount(thread) {
            // let senders = thread.map(a => a.from_mail === this.user.work_mail ? 'me' : a.from_mail);
            let self = this

            let senders = thread.map(function (element) {
                let from = self.getFromMail(element.from_mail)
                return from === ''
                    ? element.from_mail === self.user.work_mail
                        ? 'me'
                        : element.from_mail
                    : from === self.user.work_mail
                        ? 'me'
                        : from;
            })

            senders = senders.filter((item, index) => senders.indexOf(item) === index);

            return senders.join(', ');
        },

        clearViewing() {
            this.cardInbox = true;
            this.cardReadMessage = false;
            this.cardReadMessageThread = false;
        },

        async getListLanguages() {
            await this.$store.dispatch('actionGetListLanguages');
        },

        clearSearchMail() {
            this.search_mail = '';
            this.getInbox();
        },

        checkEmail(email) {
            let result = '';
            if (email.indexOf("|") > 0) {
                var spl = email.split("|")

                for (var index in spl) {
                    result += '<' + spl[index] + '> ';
                }
            } else {
                result = '<' + email + '>';
            }

            return result;
        },

        doReply(route, info = null) {

            this.clearMessageform();
            // this.replyContent.email.push(this.viewContent.from_mail);

            this.replyContent.email = [];
            this.replyContent.title = route !== 'Trash'
                ? info
                    ? info.subject
                    : this.viewContentThread.inbox.subject
                : this.viewContent.subject;

            this.$refs.emailReplyBalloon.open();

            // add email
            if (route !== 'Trash') {

                // if (route !== 'Starred') {

                    if (this.viewContentThread.inbox.is_sent === 1) {

                        let rec = info ? info.sender : this.viewContentThread.inbox.received

                        this.replyContent.email = createTags(rec.replace(/\s/g, '')
                            .split(/[|,]/g)
                            .filter(function (email) {
                                return email !== '';
                            }))
                    } else {
                        let tag_email = (this.viewContentThread.inbox.from_mail === ''
                            || this.viewContentThread.inbox.from_mail === '<>')
                            ? this.viewContentThread.inbox.sender
                            : this.viewContentThread.inbox.from_mail;

                        let tag = createTag(tag_email, [tag_email]);

                        this.$nextTick(() => {
                            this.$refs.replyTag.addTag(tag);
                        });
                    }

                // } else {
                //     this.replyContent.email = createTags(info.sender.replace(/\s/g, '')
                //         .split(/[|,]/g)
                //         .filter(function (email) {
                //             return email !== '';
                //         }))
                // }
            } else {

                if (this.viewContent.is_sent === 1) {
                    this.replyContent.email = createTags(this.viewContent.received.replace(/\s/g, '')
                        .split(/[|,]/g)
                        .filter(function (email) {
                            return email !== '';
                        }))
                } else {
                    let tag = createTag(this.viewContent.from_mail, [this.viewContent.from_mail]);

                    this.$nextTick(() => {
                        this.$refs.replyTag.addTag(tag);
                    });
                }

            }

            // clear added attachments
            this.$nextTick(() => {
                this.clearAttachments('reply');
            });

            axios.post('/api/mail/get-reply', {
                email : route !== 'Trash' ? this.viewContentThread.inbox.from_mail : this.viewContent.from_mail,
            })
            .then((res) => {
                // console.log(res.data)
            })
        },

        doForward(route, info = null) {

            this.clearMessageform();

            this.forwardContent.is_sent = info.is_sent
            this.forwardContent.email = [];
            this.forwardContent.title = route !== 'Trash'
                ? info
                    ? info.subject
                    : this.viewContentThread.inbox.subject
                : this.viewContent.subject;

            // set title(subject)
            this.forwardContent.title = 'Fwd: ' + this.forwardContent.title

            // open forward balloon
            this.$refs.emailForwardBalloon.open();

            // set content
            let body = JSON.parse(info.body);
            let body_html = JSON.parse(info.body_html);
            let stripped_html = JSON.parse(info.stripped_html);

            // create forward header
            let header = this.createForwardHeader(info)

            this.forwardContent.content = body_html
                ? header + body_html['body-html']
                : stripped_html
                    ? header + stripped_html['stripped-html']
                    : header + body['body-plain']

            // add forward attachments
            if (info.is_sent === 0) {
                this.forwardContent.forwardAttachments = (info.duration_date < 30 && info.stored_attachments)
                    ? info.stored_attachments
                    : null;
            } else {
                this.forwardContent.forwardAttachments = info.attachment.length !== 0 ? info.attachment : null;
            }

            // clear added attachments
            this.$nextTick(() => {
                this.clearAttachments('forward');
            });
        },

        createForwardHeader(info) {
            return "<<< Forwarded message >>>"
                + "<div> <span>From: " + info.from_mail + "</span> <br>"
                + "<span>Date: " + info.full_clean_date + "</span> <br>"
                + "<span>Subject: " + info.subject + "</span> <br>"
                + "<span>To: " + info.received + "</span>"
                + "</div> <br> <br> <br>"
        },

        toggleTemplate(mode) {
            if (mode === 'Send') {
                this.withTemplateCompose = !this.withTemplateCompose;
            } else {
                this.withTemplateReply = !this.withTemplateReply;
            }
        },

        toggleBcc(mode) {
            if (mode === 'Send') {
                this.withBccCompose = !this.withBccCompose;
            } else if (mode === 'Reply') {
                this.withBccReply = !this.withBccReply;
            } else {
                this.withBccForward = !this.withBccForward;
            }
        },

        deleteMessage(id, index, is_all) {

            if (this.$route.name !== 'Trash') {
                this.deleteMessageThread(null, 'all')
            } else {
                swal.fire({
                    title : "Are you sure ?",
                    html : "Delete these message?",
                    icon : "warning",
                    showCancelButton : true,
                    confirmButtonText : 'Yes, delete it!',
                    cancelButtonText : 'No, keep it'
                })
                    .then((result) => {
                        if (result.isConfirmed) {

                            let ids = [];
                            for (var chk in this.checkIds) {
                                ids.push(this.checkIds[chk].id);
                            }

                            axios.get('/api/mail/delete-message', {params : {id : is_all ? ids : id}})
                                .then((res) => {
                                    if (is_all) {
                                        this.getInbox();
                                        this.paginate.total -= ids.length;
                                    } else {
                                        this.records.data.splice(index, 1);
                                        this.paginate.total--;
                                    }
                                })

                            this.selectedMessage = true;
                            this.MessageDisplay = false;
                            this.viewContent = {
                                received : '',
                                is_sent : '',
                                from_mail : '',
                                index : '',
                                id : '',
                                date : '',
                                from : '',
                                subject : '',
                                strippedHtml : '',
                                deleted_at : '',
                                attachment : {
                                    url : '',
                                    size : '',
                                    name : '',
                                },
                            }

                            this.checkIds = [];

                            this.clearViewing()

                            swal.fire(
                                'Deleted!',
                                'Messages is move to Trash.',
                                'success'
                            )
                        }
                    });
            }
        },

        deleteMessageThread(thread, mod) {
            let self = this

            swal.fire({
                title : mod === 'single' ? "Delete thread" : "Delete selected threads",
                html : mod === 'single' ? "Delete all emails in this thread?" : "Delete all emails in selected threads?",
                icon : "warning",
                showCancelButton : true,
                confirmButtonText : 'Yes, delete them!',
                cancelButtonText : 'No, keep them.'
            })
                .then((result) => {
                    if (result.isConfirmed) {

                        let ids = self.getThreadIds(thread, mod)

                        axios.post('/api/mail/delete-message-thread', {ids : ids})
                            .then((res) => {
                                self.clearViewing()
                                self.getInbox()
                                self.checkIds = [];
                                self.checkSelected()

                                if (mod !== 'single') {
                                    self.allSelected = !self.allSelected
                                }

                                swal.fire(
                                    'Deleted!',
                                    'Deleted emails moved to trash.',
                                    'success'
                                )
                            })
                    }
                })
        },

        getThreadIds(thread, mod) {
            let result = [];
            let self = this

            if (mod === 'single') {
                result = thread.map(a => a.id);
            } else {
                self.checkIds.forEach(function (item) {
                    let ids = item.thread.map(a => a.id)
                    result = result.concat(ids);
                })
            }

            return result;
        },

        selectAll() {
            this.checkIds = [];
            if (!this.allSelected) {
                for (var index in this.records.data) {
                    this.checkIds.push(this.records.data[index]);
                }
                this.btnEnable = false;
            } else {
                this.btnEnable = true;
            }
        },

        submitLabel() {
            let ids = [];
            for (var chk in this.checkIds) {
                ids.push(this.checkIds[chk].id);
            }

            axios.post('/api/mail/labeling', {
                ids : ids,
                label_id : this.updateModel.label_id
            })
                .then((res) => {

                    // for (var rec in this.records.data) {
                    //     for (var chk in this.checkIds) {
                    //         if (this.checkIds[chk].id == this.records.data[rec].id) {
                    //             this.records.data[rec].label_id = this.updateModel.label_id;
                    //         }
                    //     }
                    // }

                    this.getInbox()

                    $("#modal-label-selection").modal('hide')

                })
        },

        submitLabelThread() {
            let self = this

            if (self.$route.name === 'Trash') {
                self.submitLabel()
            } else {
                let ids = self.getThreadIds(null, 'all')

                axios.post('/api/mail/labeling-thread', {
                    ids : ids,
                    label_id : this.updateModel.label_id
                })
                    .then((res) => {
                        self.getInbox()
                        $("#modal-label-selection").modal('hide')
                    })
            }
        },

        async getTemplateList(mode) {
            let countryId = mode === 'Send' ? this.countryMailIdCompose : this.countryMailIdReply;

            await this.$store.dispatch('getListMails', {params : {country_id : countryId, full_page : 1}});

            if (mode === 'Send') {
                this.listMailTemplateCompose = this.listMailTemplate
            } else {
                this.listMailTemplateReply = this.listMailTemplate
            }
        },

        getTemplate(mod) {
            let content = mod === 'send' ? this.emailContent : this.replyContent;

            let template = mod === 'send'
                ? this.listMailTemplateCompose.data.filter(item => item.id === this.mailInfoCompose)[0]
                : this.listMailTemplateReply.data.filter(item => item.id === this.mailInfoReply)[0]

            template['content'] = this.convertLineBreaks(template['content'])

            for (let key in template) {
                if (template.hasOwnProperty(key)) {
                    content[key] = template[key]
                }
            }
        },

        convertLineBreaks(str) {
            return str.replace(/(?:\r\n|\r|\n)/g, '<br />');
        },

        async getListCountries(params) {
            await this.$store.dispatch('actionGetListCountries', params);
        },

        submitStarred(id, is_starred, is_all, position, inbox = null) {
            let id_mail = is_all ? this.checkIds : id;

            if (!is_all) {
                this.records.data[position].is_starred = is_starred == 1 ? 0 : 1;
            } else {
                for (var rec in this.records.data) {
                    for (var chk in this.checkIds) {
                        if (this.checkIds[chk].id == this.records.data[rec].id) {
                            this.records.data[rec].is_starred = 1;
                        }
                    }
                }
            }

            axios.get('/api/mail/starred', {
                params : {
                    id : id_mail,
                    is_starred : is_starred,
                }
            })
                .then((res) => {
                    if (is_all) {
                        this.getInbox();
                    }
                })
        },

        submitStarredThread(inbox, index, mod) {
            let self = this;
            let starred = 1;
            let ids = []
            let thread = null

            if (mod === 'single') {
                thread = inbox.thread
                starred = self.checkIsStarredForThreads(thread) ? 0 : 1;

                self.records.data[index].is_starred = starred

                self.records.data[index].thread.forEach(function (item) {
                    item.is_starred = starred
                })
            }

            ids = self.getThreadIds(thread, mod)

            axios.post('/api/mail/starred-thread', {
                id : ids,
                is_starred : starred,
            })
                .then((res) => {
                    if (mod !== 'single') {
                        self.getInbox();
                    }

                    self.checkIds = [];
                    self.checkSelected()
                    self.clearViewing()

                    if (mod !== 'single') {
                        self.allSelected = !self.allSelected
                    }
                })
        },

        markAsReadSelectedEmails () {
            let loader = this.$loading.show();

            let idsObject = this.getIsNotViewedEmailIds('read');

            if (idsObject.ids.length) {
                axios.post('/api/mail/is-viewed-thread', {
                    ids : idsObject.ids,
                    mode: 'read'
                })
                .then((response) => {

                    let readCount = [... new Set(idsObject.threads)].length;

                    // update unread messages in inbox count on parent component
                    this.updateInboxUnreadCountInParent(this.inboxCount - 1, this.user.work_mail, 'mark', readCount);

                    // update value of unread emails in navbar
                    if (this.user.work_mail === this.user.work_mail_orig) {
                        this.updateUnreadEmailsCount({
                            count: readCount,
                            mode: 'decrement'
                        });
                    }

                    this.markRecordDataAsRead(idsObject.threads, 'decrement');

                    this.checkIds = [];
                    this.btnEnable = true;

                    swal.fire(
                        'Mark as Read',
                        'Successfully marked selected emails as read',
                        'success'
                    )

                    loader.hide();
                })
                .catch((err) => {
                    console.log(err)

                    swal.fire(
                        'Error',
                        'Something went wrong while marking the selected emails as read',
                        'error'
                    )

                    loader.hide();
                })
            } else {
                swal.fire(
                    'Selected Emails are already read',
                    '',
                    'warning'
                )

                loader.hide();
            }
        },

        markAsUnReadSelectedEmails () {
            let loader = this.$loading.show();

            let idsObject = this.getIsNotViewedEmailIds('unread');

            if (idsObject.ids.length) {
                axios.post('/api/mail/is-viewed-thread', {
                    ids : idsObject.ids,
                    mode: 'unread'
                })
                .then((response) => {

                    let unreadCount = [... new Set(idsObject.threads)].length;

                    // update unread messages in inbox count on parent component
                    this.updateInboxUnreadCountInParent(this.inboxCount - 1, this.user.work_mail, 'unread', unreadCount);

                    // update value of unread emails in navbar
                    if (this.user.work_mail === this.user.work_mail_orig) {
                        this.updateUnreadEmailsCount({
                            count: unreadCount,
                            mode: 'increment'
                        });
                    }

                    this.markRecordDataAsRead(idsObject.threads, 'increment');

                    this.checkIds = [];
                    this.btnEnable = true;

                    swal.fire(
                        'Mark as Unread',
                        'Successfully marked selected emails as unread',
                        'success'
                    )

                    loader.hide();
                })
                .catch((err) => {
                    console.log(err)

                    swal.fire(
                        'Error',
                        'Something went wrong while marking the selected emails as unread',
                        'error'
                    )

                    loader.hide();
                })
            } else {
                swal.fire(
                    'Selected Emails are already unread',
                    '',
                    'warning'
                )

                loader.hide();
            }
        },

        getIsNotViewedEmailIds (mod) {
            let ids = [];
            let thread_ids = [];
            let compare = mod === 'read' ? 0 : 1;

            this.checkIds.forEach(function (email) {
                if (email.thread) {
                    email.thread.forEach(function (item) {
                        if (item.is_viewed === compare) {
                            ids.push(item.id)
                            thread_ids.push(email.id)
                        }
                    })
                }
            })

            return {
                ids : ids,
                threads: thread_ids
            }
        },

        markRecordDataAsRead (ids, mode) {
            let self = this;
            let viewed = mode === 'decrement' ? 1 : 0;

            self.records.data.forEach(function(record, index) {
                if (ids.includes(record.id)) {
                    self.records.data[index].thread.map(item => {
                        item.is_viewed = viewed;
                    })
                }
            })
        },

        checkSelected() {
            this.btnEnable = true;
            if (this.checkIds.length > 0) {
                this.btnEnable = false;
            }
        },

        clearCheckIds() {
            this.checkIds = []
            this.btnEnable = true;
        },

        refeshInbox() {
            let label = this.$route.query.label_id;

            if (label !== undefined || label !== 'undefined') {
                this.$parent.setQueryLabel(label);
            }

            this.getInbox();
            this.$parent.getListEmails();
        },

        bytesToSize(bytes) {
            var sizes = [
                'Bytes',
                'KB',
                'MB',
                'GB',
                'TB'
            ];
            if (bytes == 0) return '0 Byte';
            var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },

        viewMessage(inbox, index, route) {
            if (route !== 'Trash') {
                this.viewMessageThread(inbox, index)
            } else {
                let body = JSON.parse(inbox.body);
                let body_html = JSON.parse(inbox.body_html);
                let stripped_html = JSON.parse(inbox.stripped_html);

                let content = body_html
                    ? body_html['body-html']
                    : stripped_html
                        ? stripped_html['stripped-html']
                        : body['body-plain']
                let from_mail = inbox.from_mail;
                let is_sent = inbox.is_sent;
                let reply_to = '';

                if ((inbox.attachment != '' && inbox.attachment != '[]') || inbox.stored_attachments != null) {

                    if (inbox.stored_attachments != null) {
                        this.viewContent.stored_attachments = JSON.parse(inbox.stored_attachments);
                    } else {
                        if (inbox.attachment != '' && inbox.attachment != '[]') {
                            let attach = JSON.parse(inbox.attachment);
                            this.viewContent.attachment = attach;

                            if (is_sent == 0) {
                                for (let index in attach) {
                                    axios.post('/api/mail/show-attachment', {
                                        url : attach[index]['url']
                                    }, {responseType : 'arraybuffer'})
                                        .then((res) => {

                                            let blob = new Blob([res.data], {type : res.headers['content-type']})
                                            var res = res.headers['content-type'].split("/");

                                            let link = document.getElementById('link-download-href-' + index);
                                            link.href = window.URL.createObjectURL(blob);
                                            link.download = attach[index]['name'];
                                        })
                                        .catch((error) => {
                                            let link = document.getElementById('link-download-href-' + index);

                                            link.removeAttribute('href');
                                            link.innerHTML = 'Deleted';
                                            link.style.color = 'red';
                                        });
                                }
                            }
                        }

                        this.viewContent.stored_attachments = null
                    }

                } else {
                    this.viewContent.attachment = {
                        url : '',
                        size : '',
                        name : '',
                    }

                    this.viewContent.stored_attachments = null
                }

                reply_to = this.getFromMail(from_mail)

                content = '<pre style="white-space: pre-line;"> <base target="_blank">' + content + '</pre>'

                this.selectedMessage = false;
                this.MessageDisplay = true;
                this.viewContent.from = inbox.from_mail;
                this.viewContent.strippedHtml = content;
                this.viewContent.date = inbox.full_clean_date;
                this.viewContent.subject = inbox.subject;
                this.viewContent.index = index;
                this.viewContent.id = inbox.id;
                this.viewContent.deleted_at = inbox.deleted_at;
                this.viewContent.from_mail = reply_to == '' ? inbox.from_mail : reply_to;
                this.viewContent.is_sent = is_sent;
                this.viewContent.received = inbox.received;

                if (inbox.is_viewed == 0) {
                    axios.get('/api/mail/is-viewed', {params : {id : inbox.id}})
                    this.records.data[index].is_viewed = 1
                }

                this.cardInbox = false;
                this.cardReadMessage = true;

                this.iFrameLoader(this.viewContent.strippedHtml)
            }
        },

        viewMessageThread(inbox, inbox_index) {
            let self = this;
            let reply_to = ''
            let viewed_emails = [];

            self.cardInbox = false;
            self.MessageDisplay = true;
            self.cardReadMessageThread = true;

            self.viewContentThread.inbox = inbox
            self.viewContentThread.threads = JSON.parse(JSON.stringify(inbox.thread));

            let from_mail = self.viewContentThread.inbox.from_mail

            // get from_mail
            reply_to = this.getFromMail(from_mail)

            self.viewContentThread.inbox.from_mail = reply_to === '' ? inbox.from_mail : reply_to;

            // load email body in iframe
            self.iFrameLoaderThread(self.viewContentThread.threads)

            // attachments
            self.viewContentThread.threads.forEach(function (email, index) {
                self.emailAttachmentSorter(email, email.attachment, index)
                if (email.is_viewed === 0) {
                    viewed_emails.push(email.id)
                    self.records.data[inbox_index].thread[index].is_viewed = 1
                }
            });

            // is_viewed function
            if (viewed_emails.length !== 0) {
                axios.post('/api/mail/is-viewed-thread', {ids : viewed_emails})

                // update unread messages in inbox count on parent component
                this.updateInboxUnreadCountInParent(this.inboxCount - 1, this.user.work_mail, 'decrement');

                // update value of unread emails in navbar
                if (this.user.work_mail === this.user.work_mail_orig) {
                    this.updateUnreadEmailsCount({
                        count: 1,
                        mode: 'decrement'
                    });
                }
            }
        },

        viewAttachmentChecker(email) {
            return (email.attachment.url !== '' && email.attachment.url !== '[]') || email.stored_attachments !== null;
        },

        emailAttachmentSorter(inbox, attachments, index) {
            if ((attachments !== '' && attachments !== '[]') || inbox.stored_attachments != null) {

                if (inbox.stored_attachments != null) {
                    this.viewContentThread.threads[index].stored_attachments = JSON.parse(inbox.stored_attachments)
                } else {
                    if (attachments !== '' && attachments !== '[]') {
                        let attach = JSON.parse(attachments);
                        this.viewContentThread.threads[index].attachment = attach

                        if (inbox.is_sent === 0) {
                            attach.forEach(function (att, ind) {
                                axios.post('/api/mail/show-attachment', {
                                    url : att.url
                                }, {responseType : 'arraybuffer'})
                                    .then((res) => {

                                        let blob = new Blob([res.data], {type : res.headers['content-type']})
                                        let result = res.headers['content-type'].split("/");

                                        let link = document.getElementById('link-download-href-' + ind + inbox.id);
                                        link.href = window.URL.createObjectURL(blob);
                                        link.download = att.name;
                                    })
                                    .catch((err) => {
                                        console.log(err)
                                        let link = document.getElementById('link-download-href-' + ind + inbox.id);

                                        link.removeAttribute('href');
                                        link.innerHTML = 'Deleted';
                                        link.style.color = 'red';
                                    })
                            });
                        }
                    }

                    this.viewContentThread.threads[index].stored_attachments = null
                }

            } else {
                this.viewContentThread.threads[index].attachment = {
                    url : '',
                    size : '',
                    name : '',
                }

                this.viewContentThread.threads[index].stored_attachments = null
            }
        },

        iFrameLoaderThread(thread) {
            let self = this;

            this.$nextTick(() => {
                thread.forEach(function (email) {

                    let body = JSON.parse(email.body);
                    let body_html = JSON.parse(email.body_html);
                    let stripped_html = JSON.parse(email.stripped_html);

                    let content = body_html
                        ? body_html['body-html']
                        : stripped_html
                            ? stripped_html['stripped-html']
                            : body['body-plain']

                    content = '<pre style="white-space: pre-line;"> <base target="_blank">' + content + '</pre>'

                    let iFrameElement = self.$refs['iframe' + email.id][0]
                    let doc = iFrameElement.contentWindow.document
                    doc.open()
                    doc.write(content)
                    doc.close()

                    // default hide quoted messages
                    let quotedElement = $('#iframe' + email.id).contents().find('.gmail_quote');

                    if (quotedElement.length !== 0) {
                        quotedElement.hide();
                    } else {
                        $('#toggle' + email.id).hide()
                    }

                    iFrameElement.onload = () => {
                        iFrameElement.style.height = "0"
                        iFrameElement.style.height = doc.body.scrollHeight + 'px';
                    }
                });
            });
        },

        iFrameLoader(iframeBody) {
            const iFrameEl = this.$refs.iframe
            const doc = iFrameEl.contentWindow.document
            doc.open()
            doc.write(iframeBody)
            doc.close()
            iFrameEl.onload = () => {
                iFrameEl.style.height = "0"
                this.$nextTick(() => {
                    iFrameEl.style.height = doc.body.scrollHeight + 'px';
                });
            }
        },

        toggleQuotedMessage(iframeElement) {
            $('#' + iframeElement).contents().find('.gmail_quote').toggle();

            const iframeEl = this.$refs[iframeElement][0];
            const doc = iframeEl.contentWindow.document

            iframeEl.style.height = "0"
            this.$nextTick(() => {
                iframeEl.style.height = doc.body.scrollHeight + 'px';
            });
        },

        getFromMail(from_mail) {
            let reply_to = '';

            if (from_mail.search("<") >= 0) {
                let spl = from_mail.split("<")[1]
                reply_to = spl.slice(0, -1);
            }

            return reply_to;
        },

        async sendEmail(type) {
            let cc = '';
            let appendEmail = '';
            let appendTitle = '';
            let appendContent = '';
            let attachments = [];
            let forwardAttachments = [];

            if (type == 'reply') {
                cc = (typeof (this.replyContent.cc) == "undefined") ? "" : this.replyContent.cc;
                appendTitle = this.replyContent.title;
                appendContent = this.replyContent.content;
                appendEmail = JSON.stringify(this.replyContent.email);

                attachments = this.$refs.file_reply.files;
            } else if (type == 'forward') {
                cc = (typeof (this.forwardContent.cc) == "undefined") ? "" : this.forwardContent.cc;
                appendTitle = this.forwardContent.title;
                appendContent = this.forwardContent.content;
                appendEmail = JSON.stringify(this.forwardContent.email);

                attachments = this.$refs.file_forward.files;
            } else {
                cc = (typeof (this.emailContent.cc) == "undefined") ? "" : this.emailContent.cc;
                appendTitle = this.emailContent.title;
                appendContent = this.emailContent.content;
                appendEmail = JSON.stringify(this.emailContent.email);

                attachments = this.$refs.file_send.files;
            }

            // request body
            this.formData = new FormData();
            this.formData.append('cc', cc);
            this.formData.append('email', appendEmail);
            this.formData.append('title', appendTitle);
            this.formData.append('type', type);
            this.formData.append('work_mail', this.user.work_mail);
            this.formData.append('content', appendContent);

            // attachments
            if (!attachments.length) {
                this.formData.append('attachment', 'undefined');
            } else {
                for (let i = 0; i < attachments.length; i++) {
                    this.formData.append('attachment[]', attachments[i]);
                }
            }

            // forward attachments
            if (type === 'forward') {
                if (this.forwardContent.forwardAttachments) {
                    forwardAttachments = this.forwardContent.forwardAttachments
                }
            }

            if (!forwardAttachments.length) {
                this.formData.append('forwardAttachment', 'undefined');
            } else {
                for (let i = 0; i < forwardAttachments.length; i++) {
                    this.formData.append('forwardAttachment[]', JSON.stringify(forwardAttachments[i]));
                }

                this.formData.append('is_sent', this.forwardContent.is_sent);
            }

            await this.$store.dispatch('actionSendMailgun', this.formData);

            if (this.messageForms.action == 'success') {

                swal.fire(
                    'Send',
                    'Successfully Send Email',
                    'success'
                )

                // delete removed images on editor
                if (type === 'compose') {
                    this.$refs.composeEditor.deleteImages('Removed');
                } else if (type === 'reply') {
                    this.$refs.replyEditor.deleteImages('Removed');
                }

                // close balloon
                this.closeModal(type);

                //clear model
                this.clearModel(type);

                // clear attachments and templates
                if (type === 'compose') {
                    this.$refs.file_send.value = ""

                    this.countryMailIdCompose = '';
                    this.mailInfoCompose = {};

                } else if (type === 'reply') {
                    this.$refs.file_reply.value = ""

                    this.countryMailIdReply = '';
                    this.mailInfoReply = {};
                } else {
                    this.$refs.file_forward.value = ""
                }

                // clear attachment count
                this.attached_files[type] = 0;

                this.getInbox();

                // clear message forms
                this.clearMessageform()
            } else {
                await swal.fire(
                    'Error',
                    'There are some errors while sending the email',
                    'error'
                )
            }
        },

        getStatus() {
            axios.get('/api/mail/status')
                .then((res) => {
                    // console.log(res.data)
                })
        },

        toggleShowUnreadMessages() {
            this.toggleShowUnreadEmails = !this.toggleShowUnreadEmails;

            this.getInbox();
        },

        getInbox(page = 1) {
            //    this.loadingMessage = true;
            let loader = this.$loading.show();
            if (this.user.work_mail) {
                axios.get('/api/mail/filter-recipient?page=' + page, {
                    params : {
                        'email' : this.user.work_mail,
                        'param' : this.$route.name,
                        'search_mail' : this.search_mail,
                        'label_id' : this.$route.query.label_id,
                        'mail_id' : this.$route.query.mail_id,
                        'toggle_unread': this.toggleShowUnreadEmails
                    }
                })
                    .then((response) => {
                        this.records = response.data.inbox;
                        this.inboxCount = response.data.count;

                        this.updateInboxUnreadCountInParent(this.inboxCount, this.user.work_mail, 'load');

                        this.paginate.to = this.records.to == null ? 0 : this.records.to;
                        this.paginate.total = this.records.total == null ? 0 : this.records.total;
                        this.paginate.from = this.records.from == null ? 0 : this.records.from;

                        if (this.records.next_page_url != null) {
                            let page = this.records.next_page_url.split("=")[1];
                            this.paginate.next = page;
                        }

                        if (this.records.prev_page_url != null) {
                            let page = this.records.prev_page_url.split("=")[1];
                            this.paginate.prev = page;
                        }

                        loader.hide();
                    })
                    .catch((error) => {
                        console.log(error);
                        error => error;
                        loader.hide();
                    });
            }

        },

        clearMessageform() {
            this.$store.dispatch('clearMessageform');
        },

        checkEmailValidationError(error) {
            return Object.keys(error).some(function (err) {
                return ~err.indexOf("email.")
            })
        },

        updateInboxUnreadCountInParent(count, mail, mode, thread = null) {
            this.$emit('updateInboxUnreadCount', {count: count, mail: mail, mode: mode, thread: thread})
        },

        retrieveDeletedMessage() {
            axios.post('/api/mail/retrieve-deleted-email', {
                ids : this.checkIds
            }).then((response) => {
                swal.fire(
                    'Send',
                    'Successfully Retrieved Email',
                    'success'
                )

                this.getInbox();
            });
        },

        statusClass(status) {
            return {
                'bg-warning': status === 0,
                'bg-maroon': status === 500,
                'bg-gray-dark': status === 570,
                'bg-danger': status === 552,
                'bg-success': status === 250,
                'bg-navy': status === 260
            }
        },

        statusLabel(code) {
            let label = '';

            if (code === 0) {
                label = 'Sent'
            } else if(code === 250){
                label = 'Delivered'
            } else if(code === 500){
                label = 'Rejected'
            } else if(code === 552){
                label = 'Failed'
            } else if(code === 260){
                label = 'Opened'
            } else {
                label = 'Reported'
            }

            return label;
        },

        getAttachments(mode) {
            let attachments = [];

            if (mode === 'reply') {
                attachments = this.$refs.file_reply.files
            } else if (mode === 'forward') {
                attachments = this.$refs.file_forward.files
            } else {
                attachments = this.$refs.file_send.files
            }

            this.attached_files[mode] = attachments.length
        },

        clearAttachments(type) {
            // clear attachments and templates
            if (type === 'forward') {
                this.$refs.file_forward.value = ""

            } else if (type === 'reply') {
                this.$refs.file_reply.value = ""
            }

            // clear attachment count
            this.attached_files[type] = 0;
        },

        // drafts methods

        saveMessageAsDraft(data) {
            axios.post('/api/mail/save-draft', data).then((response) => {
                swal.fire({
                    title: 'Message saved as draft!',
                    icon: 'success'
                });
            });
        },
    }
}
</script>

<style>

.vue-tags-input {
    width: 100% !important;
    max-width: 100% !important;
    color: #495057;
    display: block;
    font-size: 1.065rem;
    font-weight: 400;
    line-height: 1.5;
    border-radius: .25rem;
    background-color: #fff;
    border: 1px solid #ced4da;
    background-clip: padding-box;
    /*height: calc(1.5em + .75rem + 2px);*/
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.vue-tags-input .ti-tag {
    background-color: floralwhite !important;
    color: #495057;
    border: 1px solid grey;
}

.ti-tag.ti-valid {
    color: #495057 !important
}

.ti-input {
    border: none !important;
    padding: 2px !important;
}

.ti-tag .ti-actions {
    margin-left: 7px !important;
    border-radius: 50%;
    border: 1px solid #495057;
}

.vue-tags-input.ti-focus .ti-input {
    outline: 0 none;
    border-color: rgba(2, 117, 216, 0.3);
    box-shadow: 0 2px 2px rgba(0, 0, 0, 0.075) inset, 0 0 10px rgba(2, 117, 216, 0.4);
}

.vue-tag-error {
    border-color: red !important;
}

/* style the placeholders color across all browser */
.vue-tags-input ::-webkit-input-placeholder {
    color: #939ba2;
}

.vue-tags-input ::-moz-placeholder {
    color: #939ba2;
}

.vue-tags-input :-ms-input-placeholder {
    color: #939ba2;
}

.vue-tags-input :-moz-placeholder {
    color: #939ba2;
}

.selected-placeholder {
    color: #939ba2;
}

/* Balloon styles */

.bln-container {
    bottom: 0;
    right: 0;
    width: 100%;
    z-index: 1040;
    position: fixed;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
    justify-content: flex-end;
    pointer-events: none;
}

</style>
