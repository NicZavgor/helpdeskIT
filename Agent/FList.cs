using System;
using System.Buffers;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Security.Principal;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using static Agent.TrayApplication;

namespace Agent
{
    public partial class FList : Form
    {
        private Request ShowRequest = null;
        private appParameters appParam;
        private Agent.FRequest FRequestForm;

        public FList()
        {
            InitializeComponent();
        }
        private void LoadData(int AStatus, int APriority, int ACategory, string ASearch, bool ADeadline)
        {
            SqlDataAdapter adapter = appParam.dB.getAllRequests(appParam.account, AStatus, APriority, ACategory, ASearch, ADeadline);
            DataTable dataTable = new DataTable();
            adapter.Fill(dataTable);
            GWRequests.DataSource = dataTable;
            GWRequests.AutoSizeColumnsMode = DataGridViewAutoSizeColumnsMode.DisplayedCells;
            GWRequests.ReadOnly = true;
        }
        public void FillFormControl(appParameters AappParam)
        {
            appParam = AappParam;
            ColPriority.DataSource = appParam.dB.GetSprInfo("urgencies", true);//GetUrgency(true);
            ColPriority.DisplayMember = "Name";
            ColPriority.ValueMember = "id";
            ColCategory.DataSource = appParam.dB.GetSprInfo("categories", true);
            ColCategory.DisplayMember = "Name";
            ColCategory.ValueMember = "id";
            ColStatus.DataSource = appParam.dB.GetSprInfo("statuses", true);//GetStatus(true);
            ColStatus.DisplayMember = "Name";
            ColStatus.ValueMember = "id";

            CBPriority.DataSource = appParam.dB.GetSprInfo("urgencies", true); //GetUrgency(true);
            CBPriority.DisplayMember = "Name";
            CBPriority.ValueMember = "id";
            CBCategory.DataSource = appParam.dB.GetSprInfo("categories", true);
            CBCategory.DisplayMember = "Name";
            CBCategory.ValueMember = "id";
            CBStatus.DataSource = appParam.dB.GetStatus(true);
            CBStatus.DisplayMember = "Name";
            CBStatus.ValueMember = "id";
            CBStatus.SelectedValue = 100;// активные
            CBCategory.SelectedValue = 0;
            CBPriority.SelectedValue = 0;
            LoadData((int)CBStatus.SelectedValue, (int)CBPriority.SelectedValue, (int)CBCategory.SelectedValue, TBSearch.Text, CBDeadline.Checked);
            GWRequests.AllowUserToAddRows = false;
            //BClose.Click += BClose_Click;

            CBPriority.SelectedValueChanged += CBStatus_SelectedValueChanged;
            CBCategory.SelectedValueChanged += CBStatus_SelectedValueChanged;
            CBStatus.SelectedValueChanged += CBStatus_SelectedValueChanged;


        }

        private void GWRequests_CellDoubleClick(object sender, DataGridViewCellEventArgs e)
        {
            int idRequest = (int)((System.Windows.Forms.DataGridView)sender).CurrentRow.Cells[0].Value;

            ShowRequest = new Request();
            ShowRequest = appParam.dB.GetRequest(idRequest);
            FRequestForm = new Agent.FRequest();
            FRequestForm.Show();
            FRequestForm.BOk.Click += BOk_Click;
            FRequestForm.WindowState = FormWindowState.Normal;
            FRequestForm.Activate();
            FRequestForm.FillFormControl(appParam);
            FRequestForm.FillFormByRequest(ShowRequest);

            /*int _id, string _pcname, string _account, string _employeename, int _urgency, string _message,
                int _status, string _requestsolving, ITRole _itrole, DateTime _dt, string _topic, int _idcategory, int _iduser, DateTime _deadline)*/

        }
        public void BOk_Click(object sender, EventArgs e)
        {
            FRequestForm.BOk.Click -= BOk_Click;//отписываемся от события
            Request FormRequest = FRequestForm.FillRequestByForm();
            appParam.dB.SaveRequest(FormRequest);
            FRequestForm.Hide();
            FRequestForm.Dispose();
            FRequestForm = null;
            LoadData((int)CBStatus.SelectedValue, (int)CBPriority.SelectedValue, (int)CBCategory.SelectedValue, TBSearch.Text, CBDeadline.Checked);
        }

        private void CBStatus_SelectedValueChanged(object sender, EventArgs e)
        {
            LoadData((int)CBStatus.SelectedValue, (int)CBPriority.SelectedValue, (int)CBCategory.SelectedValue, TBSearch.Text, CBDeadline.Checked);
        }


        private Color GetColorForStatus(int status)
        {
            switch (status)
            {
                /*dtStatus.Rows.Add(1, "Новая");
                dtStatus.Rows.Add(2, "В работе");
                dtStatus.Rows.Add(3, "На паузе");
                dtStatus.Rows.Add(4, "Закрыта");
                dtStatus.Rows.Add(5, "Отклонена");*/
                case 1: return Color.LightBlue;
                case 2: return Color.LightGray;
                case 3: return Color.LightCyan;
                case 4: return Color.LightYellow;
                case 5: return Color.LightGreen;
                default: return Color.White;
            }
        }
        private Color GetColorForPriority(int priority)
        {
            /*            
            dtUrgency.Rows.Add(1, "Пожелание");
            dtUrgency.Rows.Add(2, "Текущий ворпос");
            dtUrgency.Rows.Add(3, "Оперативно");
            dtUrgency.Rows.Add(4, "Срочно");*/
            switch (priority)
            {
                case 1: return Color.LightGreen;
                case 2: return Color.LightBlue;
                case 3: return Color.LightYellow;
                case 4: return Color.Red;
                default: return Color.White;
            }
            //return Color.White;
        }

        private void GWRequests_CellPainting(object sender, DataGridViewCellPaintingEventArgs e)
        {
            if (e.RowIndex < 0 || e.ColumnIndex < 0) return;

            // Проверяем колонку с категорией
            if (GWRequests.Columns[e.ColumnIndex].Name == "ColStatus")
            {
                var statusObj = GWRequests.Rows[e.RowIndex].Cells[e.ColumnIndex].Value;
                if (statusObj != null)
                {
                    Color rowColor1 = GetColorForStatus((int)statusObj);
                    e.CellStyle.BackColor = rowColor1;
                    e.CellStyle.ForeColor = (rowColor1.GetBrightness() > 0.6) ? Color.Black : Color.White;
                }
            }
            else
            {
                if (GWRequests.Columns[e.ColumnIndex].Name == "ColPriority")
                {
                    var priorityObj = GWRequests.Rows[e.RowIndex].Cells[e.ColumnIndex].Value;
                    if (priorityObj != null)
                    {
                        Color rowColor2 = GetColorForPriority((int)priorityObj);
                        e.CellStyle.BackColor = rowColor2;
                        e.CellStyle.ForeColor = (rowColor2.GetBrightness() > 0.6) ? Color.Black : Color.White;
                    }
                }
                else
                {
                    //красим розовым если дедлайн

                    for (int i = 0; i < GWRequests.Rows[e.RowIndex].Cells.Count; i++)
                    {
                        if (GWRequests.Columns[i].Name == "ColDeadline")
                        {
                            var deadlineObj = GWRequests.Rows[e.RowIndex].Cells[i].Value;
                            if (deadlineObj != null)
                            {
                                if ((DateTime)deadlineObj < DateTime.Now)
                                {
                                    e.CellStyle.BackColor = Color.LightPink;
                                }
                            }
                            break;
                        }
                    }
                }
            }

        }

        private void button1_Click(object sender, EventArgs e)
        {
            Close();
        }

        private void TBSearch_KeyDown(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter || e.KeyCode == Keys.Tab)
            {
                LoadData((int)CBStatus.SelectedValue, (int)CBPriority.SelectedValue, (int)CBCategory.SelectedValue, TBSearch.Text, CBDeadline.Checked);
            }
        }

        private void CBDeadline_Click(object sender, EventArgs e)
        {
            LoadData((int)CBStatus.SelectedValue, (int)CBPriority.SelectedValue, (int)CBCategory.SelectedValue, TBSearch.Text, CBDeadline.Checked);
        }
    }
}
