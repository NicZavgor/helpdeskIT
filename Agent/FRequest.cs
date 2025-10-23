using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Reflection.Metadata;
using System.Runtime.Intrinsics.Arm;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.Xml.Linq;

//using static TrayApp.TrayApplication;
using static Agent.DBClass;
using static Agent.TrayApplication;
using static System.Runtime.InteropServices.JavaScript.JSType;


namespace Agent
{
    public partial class FRequest : Form
    {
        private appParameters appParam;

        private System.Windows.Forms.Timer timer;
        public class MessageControl : Panel
        {
            private Label senderLabel;
            private Label messageLabel;
            private Label timeLabel;

            public MessageControl(string sender, string message, bool isMyMessage)
            {
                InitializeComponents(sender, message, isMyMessage);
            }

            private void InitializeComponents(string sender, string message, bool isMyMessage)
            {
                this.BackColor = isMyMessage ? Color.LightGray : Color.LightBlue;
                this.BorderStyle = BorderStyle.FixedSingle;
                this.Margin = new Padding(5);
                this.Padding = new Padding(10);
                this.Width = 200;
                this.AutoSize = true;
                this.MaximumSize = new Size(250, 0);

                // Метка отправителя
                senderLabel = new Label
                {
                    Text = sender,
                    Font = new Font("Arial", 8, FontStyle.Bold),
                    ForeColor = Color.DarkBlue,
                    AutoSize = true,
                    Location = new Point(10, 10)
                };

                // Метка времени
                timeLabel = new Label
                {
                    Text = DateTime.Now.ToString("HH:mm"),
                    Font = new Font("Arial", 8),
                    ForeColor = Color.Gray,
                    AutoSize = true,
                    Location = new Point(200, 10)
                };

                // Метка сообщения
                messageLabel = new Label
                {
                    Text = message,
                    Font = new Font("Arial", 9),
                    ForeColor = Color.Black,
                    AutoSize = true,
                    MaximumSize = new Size(280, 0),
                    Location = new Point(10, 25)
                };

                this.Controls.Add(senderLabel);
                this.Controls.Add(timeLabel);
                this.Controls.Add(messageLabel);

                // Выравнивание для своих сообщений справа
                if (isMyMessage)
                {
                    this.Anchor = AnchorStyles.Top | AnchorStyles.Right;
                    senderLabel.Text = "Я";
                    senderLabel.ForeColor = Color.DarkRed;
                }
            }
        }

        public Agent.TrayApplication.Request FormRequest;
        public FRequest()
        {
            InitializeComponent();
        }
        public void FillFormControl(appParameters _appParam)
        {
            appParam = _appParam;

            this.CBCategories.DataSource = appParam.dB.GetSprInfo("categories", false);
            this.CBCategories.DisplayMember = "Name";
            this.CBCategories.ValueMember = "Id";
            this.CBUrgency.DataSource = appParam.dB.GetSprInfo("urgencies", false);//GetUrgency(false);
            this.CBUrgency.DisplayMember = "Name";
            this.CBUrgency.ValueMember = "Id";
            this.CBItSpec.DataSource = appParam.dB.GetSprInfo("users", false);
            this.CBItSpec.DisplayMember = "Name";
            this.CBItSpec.ValueMember = "Id";
            this.CBStatus.DataSource = appParam.dB.GetSprInfo("statuses", false);//GetStatus(false);
            this.CBStatus.DisplayMember = "Name";
            this.CBStatus.ValueMember = "Id";

        }
        public void DisabledControls(bool AEnable)
        {
            //CBStatus.Enabled = AEnable;
            CBCategories.Enabled = AEnable;
            CBItSpec.Enabled = AEnable;
            CBUrgency.Enabled = AEnable;
            TBMessage.Enabled = AEnable;
            TPDeadline.Enabled = AEnable;
            TBTopic.Enabled = AEnable;
            CBItSpec.Enabled = AEnable;
            RBBoss.Enabled = AEnable;
            RBAdmin.Enabled = AEnable;
            RBItSpec.Enabled = AEnable;
            CBItSpec.Enabled = AEnable;
            RBProgrammist.Enabled = AEnable;
            TBMessage.Enabled = AEnable;
            CBItSpec.Enabled = AEnable;
            BOk.Visible = AEnable;
            BCancel.Visible = AEnable;
            BClose.Visible = !AEnable;
            flpChat.Enabled = !AEnable;
            TBChatMsg.Enabled = !AEnable;
            BChatSendMsg.Enabled = !AEnable;
            GWRequestProc.Enabled = !AEnable;

        }
        public void FillFormByRequest(Agent.TrayApplication.Request request)
        {
            DisabledControls(true);
            FormRequest = request;
            this.PCName.Text = request.pcname;
            this.UserDomainName.Text = request.account;
            this.UserDisplayName.Text = request.employeename;
            this.Text = "Заявка";
            if (request.itrole == ITRole.boss) this.RBBoss.Checked = true;
            if (request.itrole == ITRole.admin) this.RBAdmin.Checked = true;
            if (request.itrole == ITRole.progr) this.RBProgrammist.Checked = true;
            if (request.itrole == ITRole.spec)
            {
                this.RBItSpec.Checked = true;
                if (request.iduser > 0)
                {
                    this.CBItSpec.SelectedValue = request.iduser;
                }
            }
            //this.CBItSpec.SelectedValue = request.iduser;

            if (request.id != 0)
            {
                this.Text = "Заявка #" + request.id.ToString();
                DisabledControls(false);
            }
            else
            {
                flpChat.Enabled = false;
                TBChatMsg.Enabled = false;
                BChatSendMsg.Enabled = false;
                DisabledControls(true);
            }
            this.CBStatus.SelectedValue = request.status;
            this.CBCategories.SelectedValue = request.idcategory;
            this.CBUrgency.SelectedValue = request.urgency;
            this.TPDeadline.Value = request.deadline;
            this.TBTopic.Text = request.topic;
            this.TBMessage.Text = request.message;
            if (request.procTbl != null)
            {
                DataTable dataTable1 = new DataTable();
                request.procTbl.Fill(dataTable1);
                GWRequestProc.DataSource = dataTable1;
                GWRequestProc.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.DisplayedCells;
                GWRequestProc.ReadOnly = true;
                GWRequestProc.AllowUserToAddRows = false;
            }

            //AddMessage("Анна", "Привет! Как дела?", false);
            //AddMessage("Вы", "Привет! Все отлично, спасибо!", true);
            //AddMessage("Анна", "Что нового?", false);

            timer = new System.Windows.Forms.Timer();
            timer.Interval = 2000; // 1 секунда
            timer.Tick += Timer_Tick;
            timer.Start();
        }
        private void Timer_Tick(object sender, EventArgs e)
        {
            LoadChatMsg(FormRequest);

        }
        public void LoadChatMsg(Agent.TrayApplication.Request request)
        {
            var LChatMsg = appParam.dB.GetMessagesFromBase(request.id, request.chatlastmsgid);

            if (LChatMsg.Count > 0)
            {
                foreach (var msg in LChatMsg)
                {
                    if (msg.guid == request.guidemployee)
                    {
                        AddMessage("Я", msg.msg, true);
                    }
                    else
                    {
                        AddMessage("специалист", msg.msg, false);
                    }
                    request.chatlastmsgid = Math.Max(request.chatlastmsgid, msg.chatId);
                };
                //                  // Прокрутка к последнему сообщению
                //                  //const messagesContainer = $('#messages');
                //                  messagesContainer.scrollTop = messagesContainer.scrollHeight;
                //                  document.getElementById('chatlastmsgid').setAttribute('data-id', lastmsgid);
                SqlCommand cmd = new SqlCommand("update chat set isnew=0 where idrequest=@idrequest and chat.isnew=1", appParam.dB.conn);//users.id as userid, 
                cmd.Parameters.AddWithValue("@idrequest", request.id);
                cmd.ExecuteNonQuery();


            }

        }
        public Request FillRequestByForm()
        {

            FormRequest.message = this.TBMessage.Text;
            FormRequest.iduser = 0;
            if (RBBoss.Checked) FormRequest.itrole = ITRole.boss;
            if (RBAdmin.Checked) FormRequest.itrole = ITRole.admin;
            if (RBProgrammist.Checked) FormRequest.itrole = ITRole.progr;
            if (RBItSpec.Checked)
            {
                FormRequest.itrole = ITRole.spec;
                FormRequest.iduser = (int)CBItSpec.SelectedValue;
            }
            FormRequest.urgency = (int)this.CBUrgency.SelectedValue;
            FormRequest.status = (int)this.CBStatus.SelectedValue;
            FormRequest.topic = (string)this.TBTopic.Text;
            FormRequest.idcategory = (int)this.CBCategories.SelectedValue;
            FormRequest.deadline = this.TPDeadline.Value;
            return FormRequest;
        }


        private void BCancel_Click(object sender, EventArgs e)
        {
            Hide();
        }

        private void FRequest_FormClosing(object sender, FormClosingEventArgs e)
        {
            timer.Stop();
            timer.Dispose();
        }

        private void RBBoss_Click(object sender, EventArgs e)
        {
            CBItSpec.Enabled = false;
        }

        private void RBItSpec_Click(object sender, EventArgs e)
        {
            CBItSpec.Enabled = true;
        }

        private void BChatSendMsg_Click(object sender, EventArgs e)
        {
            SendMessage();
        }

        private void TBChatMsg_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter && !e.Shift)
            {
                e.SuppressKeyPress = true;
                SendMessage();
            }
        }

        private void SendMessage()
        {
            string message = TBChatMsg.Text.Trim();
            if (!string.IsNullOrEmpty(message))
            {
                appParam.dB.SendMessageToBase(FormRequest.id, FormRequest.account, TBChatMsg.Text);

                //AddMessage("Вы", message, true); // Сообщение от себя
                TBChatMsg.Clear();

                // Здесь можно добавить логику отправки сообщения
                // Например: chatService.SendMessage(message);
            }
        }

        public void AddMessage(string sender, string text, bool isMyMessage = false)
        {
            // Создание элемента сообщения
            MessageControl messageControl = new MessageControl(sender, text, isMyMessage);

            // Добавление в панель сообщений
            flpChat.Controls.Add(messageControl);

            // Прокрутка к последнему сообщению
            flpChat.ScrollControlIntoView(messageControl);
        }

        // Метод для получения входящих сообщений
        public void ReceiveMessage(string sender, string text)
        {
            if (this.InvokeRequired)
            {
                this.Invoke(new Action<string, string>(ReceiveMessage), sender, text);
                return;
            }

            AddMessage(sender, text, false);
        }

        private void bClose_Click(object sender, EventArgs e)
        {
            Hide();
        }
    }
}
